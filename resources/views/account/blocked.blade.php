<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-xl font-semibold text-ink leading-tight">
            Account Blocked
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-cloud p-8 text-center shadow-sm">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.93 4.93l14.14 14.14" />
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>

                <h3 class="mt-6 font-display text-2xl font-bold tracking-tight text-ink">
                    Your account has been blocked
                </h3>

                <p class="mx-auto mt-4 max-w-md font-body text-sm leading-6 text-slate">
                    An administrator has blocked your account, so most of GadgetFlow isn't available to you right now. If you believe this is a mistake, please reach out to our support team and we'll look into it.
                </p>

                <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a
                        href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo px-5 py-2.5 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500"
                    >
                        {{ __('Contact Support') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                        >
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
