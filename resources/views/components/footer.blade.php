<footer class="border-t border-white/10 bg-ink">
    <div class="max-w-7xl mx-auto px-4 py-14 sm:px-6 sm:py-16 lg:px-8">
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto" />
                    <span class="font-display text-lg font-semibold tracking-tight text-white">GadgetFlow</span>
                </div>
                <p class="mt-4 font-body text-sm leading-6 text-slate-400">
                    Rent the latest tech, hassle-free.
                </p>
                <div class="mt-6 flex items-center gap-3">
                    <a href="#" aria-label="Instagram" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="4" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="17" cy="7" r=".6" fill="currentColor" stroke="none" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 8.5h2.5V5h-2.5c-2.2 0-4 1.8-4 4v2H8v3.5h2V21h3.5v-6.5h2.4l.6-3.5h-3V9c0-.4.3-.5.5-.5Z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Twitter" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.5 5.5c-.7.4-1.5.6-2.3.8a3.6 3.6 0 0 0-6.2 3.3A10.2 10.2 0 0 1 4.5 6a3.6 3.6 0 0 0 1.1 4.8c-.6 0-1.2-.2-1.7-.4v.1a3.6 3.6 0 0 0 2.9 3.5c-.5.1-1.1.2-1.7.1a3.6 3.6 0 0 0 3.4 2.5A7.2 7.2 0 0 1 3 18.1a10.1 10.1 0 0 0 5.6 1.6c6.7 0 10.4-5.6 10.4-10.4v-.5c.7-.5 1.3-1.2 1.5-1.9Z" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Explore --}}
            <div>
                <h3 class="font-display text-sm font-semibold uppercase tracking-wide text-white">Explore</h3>
                <ul class="mt-5 space-y-3">
                    <li>
                        <a href="{{ route('customer.gadgets.index') }}" class="font-body text-sm text-slate-400 transition hover:text-white">Browse Gadgets</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.bundles.index', ['type' => 'wedding']) }}" class="font-body text-sm text-slate-400 transition hover:text-white">Wedding Combo</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.bundles.index', ['type' => 'short_film']) }}" class="font-body text-sm text-slate-400 transition hover:text-white">Short Film Combo</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.rentals.index') }}" class="font-body text-sm text-slate-400 transition hover:text-white">My Rentals</a>
                    </li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h3 class="font-display text-sm font-semibold uppercase tracking-wide text-white">Company</h3>
                <ul class="mt-5 space-y-3">
                    <li>
                        <a href="{{ route('about') }}" class="font-body text-sm text-slate-400 transition hover:text-white">About Us</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="font-body text-sm text-slate-400 transition hover:text-white">Contact</a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="font-body text-sm text-slate-400 transition hover:text-white">Admin Sign In</a>
                    </li>
                </ul>
            </div>

            {{-- Reach Us --}}
            <div>
                <h3 class="font-display text-sm font-semibold uppercase tracking-wide text-white">Reach Us</h3>
                <ul class="mt-5 space-y-3">
                    @foreach (explode("\n", config('company.return_address')) as $line)
                        <li class="font-body text-sm leading-6 text-slate-400">
                            @if (str_contains($line, '@'))
                                <a href="mailto:{{ $line }}" class="transition hover:text-white">{{ $line }}</a>
                            @else
                                {{ $line }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-white/10 pt-8 sm:flex-row">
            <p class="font-body text-xs text-slate-500">&copy; 2026 GadgetFlow. All rights reserved.</p>
            <p class="font-body text-xs text-slate-500">Built for renters who want it simple.</p>
        </div>
    </div>
</footer>
