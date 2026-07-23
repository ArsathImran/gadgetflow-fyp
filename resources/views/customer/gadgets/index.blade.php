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

    @php
        $featuredGadget = $gadgets->firstWhere('image');
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="mt-6 rounded-2xl bg-white shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('customer.gadgets.index') }}" class="grid gap-3 lg:grid-cols-12">
                        <div class="lg:col-span-5">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search gadgets by name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="lg:col-span-4">
                            <select
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

                        <div class="lg:col-span-3 flex flex-wrap gap-2">
                            <button type="submit" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
                                {{ __('Filter') }}
                            </button>

                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>

                    <div class="mt-8">
                        @if ($gadgets->count())
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                                @foreach($gadgets as $gadget)
                                    <a href="{{ route('customer.gadgets.show', $gadget) }}"
                                       class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">

                                        <div class="h-44 bg-cloud flex items-center justify-center overflow-hidden">
                                            @if($gadget->image)
                                                <img src="{{ asset('storage/' . $gadget->image) }}"
                                                     alt="{{ $gadget->name }}"
                                                     class="max-h-40 max-w-full object-contain group-hover:scale-105 transition duration-300">
                                            @else
                                                <span class="text-gray-400 text-sm font-body">No Image</span>
                                            @endif
                                        </div>

                                        <div class="p-4">
                                            @php
                                                $averageRating = $gadget->averageRating();
                                                $reviewsCount = $gadget->reviewsCount();
                                            @endphp
                                            <h3 class="font-display text-sm font-semibold text-ink truncate">
                                                {{ $gadget->name }}
                                            </h3>

                                            @if ($gadget->brand || $gadget->model)
                                                <p class="mt-1 font-body text-xs text-slate">
                                                    {{ collect([$gadget->brand, $gadget->model])->filter()->implode(' ') }}
                                                </p>
                                            @endif

                                            <p class="mt-1 font-body text-xs text-slate">
                                                {{ $gadget->category->name ?? 'Uncategorized' }}
                                            </p>

                                            <div class="mt-2">
                                                <x-spec-chip>
                                                    RM{{ number_format($gadget->daily_rental_price, 0) }}/day &middot; {{ $gadget->quantity }} in stock
                                                </x-spec-chip>
                                            </div>

                                            <div class="mt-3 flex items-center gap-2 text-xs">
                                                @if ($averageRating)
                                                    <span class="text-amber-500">
                                                        @for ($star = 1; $star <= 5; $star++)
                                                            <span class="{{ $star <= round($averageRating) ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                                        @endfor
                                                    </span>
                                                    <span class="font-mono font-medium text-gray-700">{{ number_format($averageRating, 1) }}</span>
                                                    <span class="font-mono text-gray-500">({{ $reviewsCount }})</span>
                                                @else
                                                    <span class="font-body text-gray-400">No reviews yet</span>
                                                @endif
                                            </div>

                                            <div class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-indigo px-3 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                                View Product
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                                <p class="text-base font-semibold text-gray-900">No gadgets found.</p>
                                <p class="mt-2 text-sm text-gray-500">Try a different search term or category filter.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        {{ $gadgets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
