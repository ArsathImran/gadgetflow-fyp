<div
    x-data="chatWidget()"
    data-history-url="{{ route('chat.history') }}"
    data-send-url="{{ route('chat.send') }}"
    @click.outside="open = false"
    class="fixed bottom-6 right-6 z-50"
>
    <style>[x-cloak] { display: none !important; }</style>

    <div class="relative h-14 w-14">
        <span
            x-show="!open && !hasOpened"
            x-cloak
            class="absolute inset-0 rounded-full bg-gradient-to-br from-indigo to-cyan opacity-75 animate-[ping_2.2s_cubic-bezier(0,0,0.2,1)_infinite]"
            aria-hidden="true"
        ></span>

        <button
            @click="toggle()"
            type="button"
            class="relative flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-indigo to-cyan text-white shadow-[0_16px_40px_rgba(79,70,229,0.35)] transition duration-300 hover:-translate-y-0.5 hover:scale-105 hover:shadow-[0_20px_50px_rgba(34,211,238,0.45)] focus:outline-none focus:ring-4 focus:ring-indigo-200"
            aria-label="Open GadgetFlow Assistant chat"
        >
            <svg x-show="!open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v8A2.5 2.5 0 0 1 17.5 16H9l-4 4v-4H6.5A2.5 2.5 0 0 1 4 13.5v-8Z" />
            </svg>
            <svg x-show="open" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
            </svg>

            <span
                x-show="hasUnread && !open"
                x-cloak
                class="absolute -right-0.5 -top-0.5 h-3.5 w-3.5 rounded-full bg-cyan-400 ring-2 ring-white"
                aria-hidden="true"
            ></span>
        </button>
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute bottom-[4.5rem] right-0 flex h-[500px] w-[350px] flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_24px_60px_rgba(11,18,32,0.25)] sm:w-[380px]"
    >
        <div class="flex items-center justify-between gap-3 bg-ink px-5 py-4">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/10 text-cyan">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v8A2.5 2.5 0 0 1 17.5 16H9l-4 4v-4H6.5A2.5 2.5 0 0 1 4 13.5v-8Z" />
                    </svg>
                </span>
                <div>
                    <p class="font-display text-sm font-semibold text-white">GadgetFlow Assistant</p>
                    <p class="font-body text-xs text-slate-300">Ask about gadgets &amp; combos</p>
                </div>
            </div>
            <button @click="open = false" type="button" class="text-slate-300 transition hover:text-white" aria-label="Close chat">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <div x-ref="messageList" class="flex-1 space-y-3 overflow-y-auto bg-cloud px-4 py-4">
            <template x-if="loading">
                <p class="text-center font-body text-xs text-slate-400">Loading conversation…</p>
            </template>

            <template x-if="!loading && messages.length === 0">
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-6 text-center">
                    <p class="font-body text-sm text-slate-500">
                        Hi! Ask me about gadgets, combos, pricing, or pickup &amp; delivery and I'll help you find the right fit.
                    </p>
                </div>
            </template>

            <template x-for="(item, index) in messages" :key="index">
                <div :class="item.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div
                        :class="item.role === 'user'
                            ? 'max-w-[80%] rounded-2xl rounded-br-sm bg-indigo px-4 py-2.5 font-body text-sm text-white'
                            : 'max-w-[80%] rounded-2xl rounded-bl-sm border border-slate-200 bg-white px-4 py-2.5 font-body text-sm text-slate-700'"
                        x-text="item.message"
                    ></div>
                </div>
            </template>

            <div x-show="sending" x-cloak class="flex justify-start">
                <div class="flex items-center gap-1 rounded-2xl rounded-bl-sm border border-slate-200 bg-white px-4 py-3">
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay: 0ms"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay: 150ms"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        <form @submit.prevent="send()" class="flex items-center gap-2 border-t border-slate-200 bg-white px-3 py-3">
            <input
                type="text"
                x-model="newMessage"
                :disabled="sending"
                placeholder="Ask about a gadget or combo…"
                class="flex-1 rounded-full border-gray-300 font-body text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <button
                type="submit"
                :disabled="sending || !newMessage.trim()"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo text-white transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-50"
                aria-label="Send message"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6-6m6 6-6 6" />
                </svg>
            </button>
        </form>
    </div>
</div>
