document.addEventListener('alpine:init', () => {
    Alpine.data('infiniteGrid', (config) => ({
        loading: false,
        nextPageUrl: config.nextPageUrl,
        loadedCount: config.loadedCount,
        total: config.total,
        observer: null,

        init() {
            if (! this.nextPageUrl) {
                return;
            }

            this.observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    this.loadMore();
                }
            }, { rootMargin: '400px' });

            this.observer.observe(this.$refs.sentinel);
        },

        async loadMore() {
            if (! this.nextPageUrl || this.loading) {
                return;
            }

            this.loading = true;

            try {
                const response = await fetch(this.nextPageUrl, {
                    headers: { Accept: 'application/json' },
                });

                if (! response.ok) {
                    throw new Error('Failed to load more results');
                }

                const data = await response.json();

                this.$refs.grid.insertAdjacentHTML('beforeend', data.html);
                this.nextPageUrl = data.next_page_url;
                this.loadedCount += data.count;

                if (! this.nextPageUrl) {
                    this.observer?.disconnect();
                }
            } catch (error) {
                console.error(error);
                this.observer?.disconnect();
            } finally {
                this.loading = false;
            }
        },
    }));
});
