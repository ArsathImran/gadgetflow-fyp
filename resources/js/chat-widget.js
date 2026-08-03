import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('chatWidget', () => ({
        open: false,
        loading: false,
        sending: false,
        historyLoaded: false,
        hasOpened: false,
        hasUnread: false,
        messages: [],
        newMessage: '',
        csrfToken: '',
        historyUrl: '',
        sendUrl: '',

        init() {
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
            this.historyUrl = this.$el.dataset.historyUrl;
            this.sendUrl = this.$el.dataset.sendUrl;
        },

        async toggle() {
            this.open = ! this.open;

            if (this.open) {
                this.hasOpened = true;
                this.hasUnread = false;
            }

            if (this.open && ! this.historyLoaded) {
                await this.loadHistory();
            }

            if (this.open) {
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        async loadHistory() {
            this.loading = true;

            try {
                const response = await fetch(this.historyUrl, {
                    headers: { Accept: 'application/json' },
                });

                if (! response.ok) {
                    throw new Error('Failed to load chat history');
                }

                const data = await response.json();
                this.messages = data.messages ?? [];
                this.historyLoaded = true;
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        async send() {
            const text = this.newMessage.trim();

            if (! text || this.sending) {
                return;
            }

            this.messages.push({ role: 'user', message: text });
            this.newMessage = '';
            this.sending = true;
            this.$nextTick(() => this.scrollToBottom());

            try {
                const response = await fetch(this.sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({ message: text }),
                });

                if (! response.ok) {
                    throw new Error('Chat request failed');
                }

                const data = await response.json();
                this.messages.push(data.message);

                if (! this.open) {
                    this.hasUnread = true;
                }
            } catch (error) {
                console.error(error);
                this.messages.push({
                    role: 'assistant',
                    message: 'Sorry, I\'m having trouble reaching the GadgetFlow Assistant right now. Please try again in a moment.',
                });
            } finally {
                this.sending = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        scrollToBottom() {
            const container = this.$refs.messageList;

            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
    }));
});
