<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $gadget->name }}
                </h2>
                <p class="text-sm text-gray-600">Full gadget details</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    {{ __('Back to Browse') }}
                </a>
                <a href="{{ route('rentals.create', $gadget->id) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                    {{ __('Request Rental') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="bg-gray-100">
                        @if ($gadget->image)
                            <img
                                src="{{ asset('storage/' . $gadget->image) }}"
                                alt="{{ $gadget->name }}"
                                class="h-full min-h-[320px] w-full object-cover"
                            >
                        @else
                            <div class="flex min-h-[320px] items-center justify-center text-sm text-gray-400">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-gray-500">{{ $gadget->category?->name ?? 'Uncategorized' }}</p>
                                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $gadget->name }}</h1>
                            </div>
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                In Stock
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Daily Rental Price</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($gadget->daily_rental_price, 2) }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Deposit</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($gadget->deposit_amount, 2) }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Quantity</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $gadget->quantity }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500">Description</h3>
                            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">
                                {{ $gadget->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
