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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 px-6 py-8 text-white shadow-sm sm:px-8">
                <div class="max-w-2xl">
                    <p class="text-sm uppercase tracking-[0.25em] text-slate-300">GadgetFlow Catalog</p>
                    <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Find the right gadget for your next rental.</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-200">
                        Search by name, narrow by category, and view only gadgets that are active and in stock.
                    </p>
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
                            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($gadgets as $gadget)
                                    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                        <div class="aspect-[4/3] bg-gray-100">
                                            @if ($gadget->image)
                                                <img
                                                    src="{{ asset('storage/' . $gadget->image) }}"
                                                    alt="{{ $gadget->name }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @else
                                                <div class="flex h-full items-center justify-center text-sm text-gray-400">
                                                    No image available
                                                </div>
                                            @endif
                                        </div>

                                        <div class="space-y-4 p-5">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $gadget->name }}</h3>
                                                    <p class="mt-1 text-sm text-gray-500">{{ $gadget->category?->name ?? '-' }}</p>
                                                </div>
                                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                    Active
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-3 gap-3 text-sm">
                                                <div class="rounded-xl bg-gray-50 p-3">
                                                    <p class="text-gray-500">Daily</p>
                                                    <p class="mt-1 font-semibold text-gray-900">{{ number_format($gadget->daily_rental_price, 2) }}</p>
                                                </div>
                                                <div class="rounded-xl bg-gray-50 p-3">
                                                    <p class="text-gray-500">Deposit</p>
                                                    <p class="mt-1 font-semibold text-gray-900">{{ number_format($gadget->deposit_amount, 2) }}</p>
                                                </div>
                                                <div class="rounded-xl bg-gray-50 p-3">
                                                    <p class="text-gray-500">Qty</p>
                                                    <p class="mt-1 font-semibold text-gray-900">{{ $gadget->quantity }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between gap-3">
                                                <a href="{{ route('customer.gadgets.show', $gadget) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                                    {{ __('View Details') }}
                                                </a>
                                            </div>
                                        </div>
                                    </article>
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
