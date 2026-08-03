@if (session('success') || session('error'))
    <div
        x-data="{
            show: true,
            type: {{ session('success') ? "'success'" : "'error'" }},
            message: @js(session('success') ?? session('error')),
        }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed inset-x-0 top-4 z-[100] flex justify-center px-4 sm:top-6"
    >
        <div
            :class="type === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700'"
            class="flex w-full max-w-md items-start gap-3 rounded-2xl border px-4 py-3 shadow-[0_20px_45px_rgba(15,23,42,0.15)]"
        >
            <span :class="type === 'success' ? 'text-green-500' : 'text-red-500'" class="mt-0.5 shrink-0">
                <svg x-show="type === 'success'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <svg x-show="type === 'error'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </span>

            <p class="flex-1 font-body text-sm font-medium" x-text="message"></p>

            <button type="button" x-on:click="show = false" class="shrink-0 text-slate-400 transition hover:text-slate-600" aria-label="Dismiss">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </div>
    </div>
@endif
