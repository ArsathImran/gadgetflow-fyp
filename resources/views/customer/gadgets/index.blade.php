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
            <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_24px_60px_rgba(15,23,42,0.12)]">
                <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.22),_transparent_45%),linear-gradient(180deg,rgba(255,255,255,0.95),rgba(236,254,255,0.95))] lg:block"></div>
                <div class="absolute -left-20 top-10 h-56 w-56 rounded-full bg-cyan-100 blur-3xl"></div>
                <div class="absolute bottom-0 right-24 h-64 w-64 rounded-full bg-blue-100 blur-3xl"></div>

                <div class="relative grid gap-10 px-6 py-10 sm:px-8 sm:py-12 lg:grid-cols-[minmax(0,1.05fr)_minmax(360px,0.95fr)] lg:items-center lg:px-12">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center rounded-full border border-cyan-200 bg-cyan-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 sm:text-sm">
                            GadgetFlow Rental Marketplace
                        </span>
                        <h1 class="mt-6 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                            Rent the Latest Tech with Ease
                        </h1>
                        <p class="mt-5 max-w-xl text-sm leading-7 text-slate-600 sm:text-base">
                            Browse smartphones, laptops, cameras, headphones, and gaming devices for hourly or daily rental with transparent pricing and reliable availability.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                Browse Gadgets
                            </a>
                            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500">
                                My Rentals
                            </a>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="rounded-3xl bg-gradient-to-br from-cyan-50 via-white to-blue-50 p-4 shadow-[0_18px_40px_rgba(14,165,233,0.12)] ring-1 ring-white/80 sm:p-6">
                            <div class="rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-900 p-6 sm:p-8">
                                <div class="mb-6 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-200">Featured Gadget</p>
                                        <p class="mt-2 text-lg font-medium text-white">
                                            {{ $featuredGadget?->name ?? 'Premium Tech Selection' }}
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-cyan-100 backdrop-blur">
                                        {{ $featuredGadget?->category?->name ?? 'Curated Pick' }}
                                    </span>
                                </div>

                                @if ($featuredGadget)
                                    <div class="overflow-hidden rounded-[1.75rem] bg-white/95 p-4 shadow-2xl">
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
                                            <p class="mt-5 text-xl font-semibold text-white">Featured Gadget</p>
                                            <p class="mt-2 text-sm leading-6 text-cyan-100/85">
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

                                        <div class="h-44 bg-gray-50 flex items-center justify-center overflow-hidden">
                                            @if($gadget->image)
                                                <img src="{{ asset('storage/' . $gadget->image) }}"
                                                     alt="{{ $gadget->name }}"
                                                     class="max-h-40 max-w-full object-contain group-hover:scale-105 transition duration-300">
                                            @else
                                                <span class="text-gray-400 text-sm">No Image</span>
                                            @endif
                                        </div>

                                        <div class="p-4">
                                            @php
                                                $averageRating = $gadget->averageRating();
                                                $reviewsCount = $gadget->reviewsCount();
                                            @endphp
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $gadget->name }}
                                            </h3>

                                            @if ($gadget->brand || $gadget->model)
                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ collect([$gadget->brand, $gadget->model])->filter()->implode(' ') }}
                                                </p>
                                            @endif

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $gadget->category->name ?? 'Uncategorized' }}
                                            </p>

                                            <p class="mt-2 text-xs font-medium text-emerald-600">
                                                Stock: {{ $gadget->quantity }} available
                                            </p>

                                            <div class="mt-3 flex items-center gap-2 text-xs">
                                                @if ($averageRating)
                                                    <span class="text-amber-500">
                                                        @for ($star = 1; $star <= 5; $star++)
                                                            <span class="{{ $star <= round($averageRating) ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                                        @endfor
                                                    </span>
                                                    <span class="font-medium text-gray-700">{{ number_format($averageRating, 1) }}</span>
                                                    <span class="text-gray-500">({{ $reviewsCount }})</span>
                                                @else
                                                    <span class="text-gray-400">No reviews yet</span>
                                                @endif
                                            </div>

                                            <div class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
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
