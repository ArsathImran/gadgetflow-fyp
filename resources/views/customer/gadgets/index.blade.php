<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Browse Gadgets') }}
            </h2>
            <p class="text-sm text-gray-600">
                Discover active gadgets that are currently available for rental.
            </p>
        </div>
    </x-slot>

    <div class="py-12 sm:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative">
            <div class="relative overflow-hidden rounded-3xl bg-ink shadow-[0_24px_60px_rgba(11,18,32,0.35)]">
                <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_45%),linear-gradient(180deg,rgba(79,70,229,0.12),transparent)] lg:block"></div>
                <div class="absolute -left-20 top-10 h-56 w-56 rounded-full bg-indigo/20 blur-3xl"></div>
                <div class="absolute bottom-0 right-24 h-64 w-64 rounded-full bg-cyan/10 blur-3xl"></div>

                <div class="relative grid gap-10 px-6 py-10 sm:px-8 sm:py-12 lg:grid-cols-[minmax(0,1.05fr)_minmax(360px,0.95fr)] lg:items-center lg:px-12">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/5 px-4 py-2 text-xs font-body font-semibold uppercase tracking-[0.22em] text-cyan sm:text-sm">
                            GadgetFlow Rental Marketplace
                        </span>
                        <h1 class="mt-6 font-display text-5xl font-bold tracking-tight text-white sm:text-6xl lg:text-7xl">
                            Rent the Latest Tech with Ease
                        </h1>
                        <p class="mt-5 max-w-xl font-body text-sm leading-7 text-slate-300 sm:text-base">
                            Browse smartphones, laptops, cameras, headphones, and gaming devices for hourly or daily rental with transparent pricing and reliable availability.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo px-6 py-3 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                Browse Gadgets
                            </a>
                            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-6 py-3 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-white/10">
                                My Rentals
                            </a>
                        </div>

                        <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/10 pt-6">
                            <div class="flex items-center gap-2 text-xs font-body font-medium text-slate-400 sm:text-sm">
                                <svg class="h-4 w-4 text-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <rect x="6.5" y="2.5" width="11" height="19" rx="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path stroke-linecap="round" d="M11 18.25h2" />
                                </svg>
                                <span>Smartphones</span>
                            </div>
                            <span class="hidden h-4 w-px bg-white/10 sm:block"></span>
                            <div class="flex items-center gap-2 text-xs font-body font-medium text-slate-400 sm:text-sm">
                                <svg class="h-4 w-4 text-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <rect x="4" y="4.5" width="16" height="11" rx="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 19.5h19l-1.4-3H3.9l-1.4 3Z" />
                                </svg>
                                <span>Laptops</span>
                            </div>
                            <span class="hidden h-4 w-px bg-white/10 sm:block"></span>
                            <div class="flex items-center gap-2 text-xs font-body font-medium text-slate-400 sm:text-sm">
                                <svg class="h-4 w-4 text-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 9.5A2 2 0 0 1 6 7.5h1.4l.9-1.6a1.5 1.5 0 0 1 1.3-.8h4.8a1.5 1.5 0 0 1 1.3.8l.9 1.6H18a2 2 0 0 1 2 2V17a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9.5Z" />
                                    <circle cx="12" cy="13" r="3.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Cameras</span>
                            </div>
                            <span class="hidden h-4 w-px bg-white/10 sm:block"></span>
                            <div class="flex items-center gap-2 text-xs font-body font-medium text-slate-400 sm:text-sm">
                                <svg class="h-4 w-4 text-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 14v-2a8 8 0 0 1 16 0v2" />
                                    <rect x="2.5" y="13" width="4" height="6" rx="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <rect x="17.5" y="13" width="4" height="6" rx="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Headphones</span>
                            </div>
                            <span class="hidden h-4 w-px bg-white/10 sm:block"></span>
                            <div class="flex items-center gap-2 text-xs font-body font-medium text-slate-400 sm:text-sm">
                                <svg class="h-4 w-4 text-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8.5h10a4.5 4.5 0 0 1 4.4 5.5l-.5 2a2.6 2.6 0 0 1-4.6 1L15 15.5H9l-1.3 1.5a2.6 2.6 0 0 1-4.6-1l-.5-2A4.5 4.5 0 0 1 7 8.5Z" />
                                    <path stroke-linecap="round" d="M8 11.5v2.4M6.8 12.7h2.4" />
                                    <circle cx="16" cy="11.7" r=".6" fill="currentColor" />
                                    <circle cx="17.6" cy="13.3" r=".6" fill="currentColor" />
                                </svg>
                                <span>Gaming Consoles</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-6 rounded-[2.5rem] bg-indigo/25 blur-3xl"></div>
                        <div class="relative rounded-3xl bg-gradient-to-br from-white/10 via-white/5 to-transparent p-4 shadow-[0_18px_50px_rgba(79,70,229,0.25)] ring-1 ring-white/10 sm:p-6">
                            <div class="rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-900 p-6 sm:p-8">
                                <div class="mb-6 flex items-center justify-between">
                                    <div>
                                        <p class="font-body text-xs font-semibold uppercase tracking-[0.22em] text-cyan">Featured Gadget</p>
                                        <p class="mt-2 font-display text-lg font-medium text-white">
                                            {{ $featuredGadget?->name ?? 'Premium Tech Selection' }}
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-body font-medium text-cyan-100 backdrop-blur">
                                        {{ $featuredGadget?->category?->name ?? 'Curated Pick' }}
                                    </span>
                                </div>

                                @if ($featuredGadget)
                                    <div class="overflow-hidden rounded-[1.75rem] bg-white/95 p-4 shadow-[0_20px_45px_rgba(34,211,238,0.25)]">
                                        <img
                                            src="{{ asset('storage/' . $featuredGadget->image) }}"
                                            alt="{{ $featuredGadget->name }}"
                                            class="h-[260px] w-full rounded-2xl object-cover sm:h-[340px]"
                                        >
                                    </div>
                                @else
                                    <div class="flex h-[260px] items-center justify-center rounded-[1.75rem] border border-dashed border-white/30 bg-white/10 p-6 text-center backdrop-blur sm:h-[340px]">
                                        <div>
                                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-cyan-100">
                                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5h16.5M6 6.75v1.5m12-1.5v1.5M6.75 20.25h10.5A2.25 2.25 0 0 0 19.5 18V9H4.5v9a2.25 2.25 0 0 0 2.25 2.25ZM8.25 12.75h7.5" />
                                                </svg>
                                            </div>
                                            <p class="mt-5 font-display text-xl font-semibold text-white">Featured Gadget</p>
                                            <p class="mt-2 font-body text-sm leading-6 text-cyan-100/85">
                                                A premium device preview will appear here as soon as an active gadget image is available.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="pointer-events-none absolute -bottom-7 left-6 z-10 hidden sm:block lg:left-12">
                    <div class="pointer-events-auto inline-flex items-center gap-3 rounded-2xl bg-white px-5 py-4 shadow-[0_18px_40px_rgba(15,23,42,0.18)]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo/10 text-indigo">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3l7.5 3.409v5.291c0 4.756-3.163 8.686-7.5 9.795-4.337-1.109-7.5-5.039-7.5-9.795V6.409L12 3Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-display text-sm font-semibold text-ink">Verified &amp; Insured</p>
                            <p class="font-body text-xs text-slate">Admin-checked before every rental</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 rounded-2xl bg-white shadow-sm sm:rounded-2xl sm:mt-20">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('customer.gadgets.index') }}" class="grid gap-3 lg:grid-cols-12">
                        <div class="lg:col-span-4">
                            <label for="filter-search" class="sr-only">Search gadgets</label>
                            <input
                                id="filter-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search gadgets by name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="lg:col-span-3">
                            <label for="filter-category" class="sr-only">Category</label>
                            <select
                                id="filter-category"
                                name="category_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="lg:col-span-3">
                            <label for="filter-price" class="sr-only">Price range</label>
                            <select
                                id="filter-price"
                                name="price_range"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="" @selected(request('price_range') === null || request('price_range') === '')>Any price</option>
                                <option value="under_50" @selected(request('price_range') === 'under_50')>Under RM50/day</option>
                                <option value="50_150" @selected(request('price_range') === '50_150')>RM50 - RM150/day</option>
                                <option value="150_plus" @selected(request('price_range') === '150_plus')>RM150+/day</option>
                            </select>
                        </div>

                        <div class="lg:col-span-2">
                            <label for="filter-availability" class="sr-only">Availability</label>
                            <select
                                id="filter-availability"
                                name="availability"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="in_stock" @selected(request('availability') !== 'any')>In stock only</option>
                                <option value="any" @selected(request('availability') === 'any')>Any availability</option>
                            </select>
                        </div>

                        <div class="lg:col-span-12 flex flex-wrap justify-end gap-2">
                            <button type="submit" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
                                {{ __('Filter') }}
                            </button>

                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>

                    @if ($gadgets->count())
                        <div
                            x-data="infiniteGrid({
                                nextPageUrl: @js($gadgets->nextPageUrl()),
                                loadedCount: {{ $gadgets->count() }},
                                total: {{ $gadgets->total() }},
                            })"
                            class="mt-8"
                        >
                            <p class="mb-4 font-body text-sm text-slate" x-text="`Showing ${loadedCount} of ${total} results`"></p>

                            <div x-ref="grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                                @include('customer.gadgets._grid-items')
                            </div>

                            <div x-ref="sentinel"></div>

                            <div x-show="loading" x-cloak class="mt-8 flex justify-center">
                                <span class="h-6 w-6 animate-spin rounded-full border-2 border-indigo border-t-transparent"></span>
                            </div>

                            <div x-show="!loading && !nextPageUrl" x-cloak class="mt-8 text-center font-body text-xs text-slate-400">
                                You&rsquo;ve reached the end of the list.
                            </div>
                        </div>
                    @else
                        <div class="mt-8 rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="text-base font-semibold text-gray-900">No gadgets found.</p>
                            <p class="mt-2 text-sm text-gray-500">Try a different search term or filter combination.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
