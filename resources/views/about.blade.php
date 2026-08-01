<x-app-layout>
    <div class="py-12 sm:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Intro / mission --}}
            <div class="relative overflow-hidden rounded-3xl bg-ink px-6 py-14 text-center shadow-[0_24px_60px_rgba(11,18,32,0.35)] sm:px-10 sm:py-16">
                <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-indigo/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-16 -right-16 h-56 w-56 rounded-full bg-cyan/10 blur-3xl"></div>
                <div class="relative">
                    <span class="inline-flex items-center rounded-full border border-white/15 bg-white/5 px-4 py-2 text-xs font-body font-semibold uppercase tracking-[0.22em] text-cyan">
                        About GadgetFlow
                    </span>
                    <h1 class="mx-auto mt-6 max-w-2xl font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Making great tech accessible, one rental at a time
                    </h1>
                    <p class="mx-auto mt-4 max-w-2xl font-body text-sm leading-7 text-slate-300 sm:text-base">
                        GadgetFlow is a rental marketplace for smartphones, laptops, cameras, and gaming gear, plus curated combo packages for weddings and short film shoots. We keep every listing admin-verified, every handover QR-checked, and every loyal renter rewarded &mdash; so you can access the tech you need without the cost or commitment of buying it outright.
                    </p>
                </div>
            </div>

            {{-- Why Choose Us --}}
            <div class="mt-16 sm:mt-20">
                <div class="text-center">
                    <p class="font-body text-xs font-semibold uppercase tracking-[0.22em] text-indigo">Why Choose Us</p>
                    <h2 class="mt-3 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">Built for trust, speed, and value</h2>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7.5 3.409v5.291c0 4.756-3.163 8.686-7.5 9.795-4.337-1.109-7.5-5.039-7.5-9.795V6.409L12 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
                            </svg>
                        </span>
                        <p class="mt-5 font-display text-lg font-semibold text-ink">Verified, Admin-Managed Listings</p>
                        <p class="mt-2 font-body text-sm leading-6 text-slate">Every gadget and combo package is reviewed and inventoried by our team before it ever goes live, so what you see is what you get.</p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <rect x="3" y="6" width="18" height="13" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path stroke-linecap="round" d="M3 10.5h18" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 15h4" />
                            </svg>
                        </span>
                        <p class="mt-5 font-display text-lg font-semibold text-ink">Secure Two-Step Payment</p>
                        <p class="mt-2 font-body text-sm leading-6 text-slate">Rental and deposit payments are confirmed separately and verified by our team, keeping every transaction transparent and protected.</p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <rect x="3.5" y="3.5" width="6" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" />
                                <rect x="14.5" y="3.5" width="6" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" />
                                <rect x="3.5" y="14.5" width="6" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 14.5h3v3h-3zM20.5 14.5v2M17.5 20.5h3" />
                            </svg>
                        </span>
                        <p class="mt-5 font-display text-lg font-semibold text-ink">QR Verified Handover &amp; Return</p>
                        <p class="mt-2 font-body text-sm leading-6 text-slate">Every pickup and return is confirmed with a unique QR scan, giving both sides a clear, tamper-proof record of condition and timing.</p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m12 3.5 2.47 5.06 5.53.8-4 3.9.94 5.5L12 16.2l-4.94 2.56.94-5.5-4-3.9 5.53-.8L12 3.5Z" />
                            </svg>
                        </span>
                        <p class="mt-5 font-display text-lg font-semibold text-ink">Loyalty Rewards</p>
                        <p class="mt-2 font-body text-sm leading-6 text-slate">Every completed rental earns points toward Bronze, Silver, and Gold tiers, unlocking better perks the more you rent with us.</p>
                    </div>
                </div>
            </div>

            {{-- Impact stats --}}
            <div class="mt-16 sm:mt-20">
                <div class="text-center">
                    <p class="font-body text-xs font-semibold uppercase tracking-[0.22em] text-indigo">Our Impact</p>
                    <h2 class="mt-3 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">Trusted by renters across Malaysia</h2>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
                        <p class="font-body text-sm font-medium text-slate">Active Gadgets</p>
                        <p class="mt-4 font-display text-5xl font-bold text-ink">{{ number_format($stats['activeGadgets']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-6 shadow-[0_18px_40px_rgba(79,70,229,0.10)]">
                        <p class="font-body text-sm font-medium text-indigo-700">Completed Rentals</p>
                        <p class="mt-4 font-display text-5xl font-bold text-indigo-900">{{ number_format($stats['completedRentals']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-6 shadow-[0_18px_40px_rgba(34,211,238,0.10)]">
                        <p class="font-body text-sm font-medium text-cyan-700">Combo Packages</p>
                        <p class="mt-4 font-display text-5xl font-bold text-cyan-900">{{ number_format($stats['comboPackages']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-[0_18px_40px_rgba(245,158,11,0.10)]">
                        <p class="font-body text-sm font-medium text-amber-700">Registered Customers</p>
                        <p class="mt-4 font-display text-5xl font-bold text-amber-900">{{ number_format($stats['registeredCustomers']) }}</p>
                    </div>
                </div>
            </div>

            @if ($testimonials->isNotEmpty())
                {{-- Testimonials --}}
                <div class="mt-16 sm:mt-20">
                    <div class="text-center">
                        <p class="font-body text-xs font-semibold uppercase tracking-[0.22em] text-indigo">Testimonials</p>
                        <h2 class="mt-3 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">What Our Customers Say</h2>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                        @foreach ($testimonials as $testimonial)
                            <div class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                                <div class="flex items-center gap-0.5">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span class="{{ $star <= $testimonial->rating ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                    @endfor
                                </div>
                                <p class="mt-4 flex-1 font-body text-sm leading-6 text-slate-700">&ldquo;{{ $testimonial->comment }}&rdquo;</p>
                                <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-4">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-sm font-semibold text-white">
                                        {{ strtoupper(substr($testimonial->user->name ?? '?', 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="font-display text-sm font-semibold text-ink">{{ $testimonial->user->name ?? 'Verified Renter' }}</p>
                                        <p class="font-body text-xs text-slate">{{ $testimonial->gadget->name ?? $testimonial->bundle->name ?? 'GadgetFlow Rental' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
