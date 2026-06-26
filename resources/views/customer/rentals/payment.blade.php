<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Upload Payment Proof') }}
                </h2>
                <p class="text-sm text-gray-600">Complete payment for {{ $rental->gadget?->name ?? 'your rental' }}.</p>
            </div>

            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                {{ __('Back to My Rentals') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1fr_0.95fr]">
                <div class="rounded-3xl bg-white p-6 shadow-sm sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900">Bank Transfer Details</h3>
                    <div class="mt-4 space-y-3 rounded-2xl bg-gray-50 p-4 text-sm text-gray-700">
                        <p><span class="font-semibold text-gray-900">Bank Name:</span> Maybank</p>
                        <p><span class="font-semibold text-gray-900">Account Name:</span> GadgetFlow</p>
                        <p><span class="font-semibold text-gray-900">Account Number:</span> 1234567890</p>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700">Rental Summary</p>
                        <div class="mt-3 grid gap-3 rounded-2xl border border-gray-200 p-4 text-sm text-gray-700">
                            <p><span class="font-semibold text-gray-900">Gadget:</span> {{ $rental->gadget?->name ?? '-' }}</p>
                            <p><span class="font-semibold text-gray-900">Total Amount:</span> {{ number_format($rental->total_amount, 2) }}</p>
                            <p><span class="font-semibold text-gray-900">Payment Status:</span> {{ ucfirst($rental->payment_status) }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm sm:p-8">
                    @if ($errors->any())
                        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            Please upload a valid payment proof file.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.rentals.payment.store', $rental) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        @if ($rental->payment_proof)
                            <div class="rounded-2xl border border-gray-200 p-4 text-sm text-gray-700">
                                <p class="font-semibold text-gray-900">Current Proof</p>
                                <a href="{{ asset('storage/' . $rental->payment_proof) }}" target="_blank" class="mt-2 inline-flex text-indigo-600 hover:text-indigo-500">
                                    View uploaded payment proof
                                </a>
                            </div>
                        @endif

                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700">Payment Proof</label>
                            <input
                                id="payment_proof"
                                name="payment_proof"
                                type="file"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                {{ __('Submit Payment Proof') }}
                            </button>

                            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
