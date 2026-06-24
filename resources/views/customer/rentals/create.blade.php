<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Request Rental') }}
                </h2>
                <p class="text-sm text-gray-600">Choose your rental dates for {{ $gadget->name }}.</p>
            </div>

            <a href="{{ route('customer.gadgets.show', $gadget) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                {{ __('Back to Gadget') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="grid gap-0 lg:grid-cols-[0.9fr_1.1fr]">
                    <div class="bg-gray-100">
                        @if ($gadget->image)
                            <img
                                src="{{ asset('storage/' . $gadget->image) }}"
                                alt="{{ $gadget->name }}"
                                class="h-full min-h-[280px] w-full object-cover"
                            >
                        @else
                            <div class="flex min-h-[280px] items-center justify-center text-sm text-gray-400">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        @if ($errors->any())
                            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                Please fix the highlighted fields and try again.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('customer.rentals.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="gadget_id" value="{{ $gadget->id }}">

                            <div>
                                <p class="text-sm font-medium text-gray-500">Gadget</p>
                                <p class="mt-1 text-base font-semibold text-gray-900">{{ $gadget->name }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $gadget->category?->name ?? '-' }}</p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input
                                        id="start_date"
                                        name="start_date"
                                        type="date"
                                        value="{{ old('start_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input
                                        id="end_date"
                                        name="end_date"
                                        type="date"
                                        value="{{ old('end_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-4 text-sm text-gray-700">
                                <p class="font-semibold text-gray-900">Pricing</p>
                                <p class="mt-2">Daily rental price: {{ number_format($gadget->daily_rental_price, 2) }}</p>
                                <p>Deposit: {{ number_format($gadget->deposit_amount, 2) }}</p>
                                <p class="mt-2 text-gray-500">Total amount will be calculated from your rental dates.</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                    {{ __('Submit Request') }}
                                </button>

                                <a href="{{ route('customer.gadgets.show', $gadget) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
