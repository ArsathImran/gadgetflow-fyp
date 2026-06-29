<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Upload Payment Proof') }}
                </h2>
                <p class="text-sm text-gray-600">Complete payment for {{ $rental->gadget?->name ?? 'your rental' }}.</p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-data
                    x-on:click="$dispatch('open-modal', 'payment-qr-modal')"
                    class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                >
                    <img src="{{ asset('images/payment-qr.svg') }}" alt="Payment QR" class="h-5 w-5 rounded-sm border border-gray-200 object-cover">
                    <span>View QR</span>
                </button>

                <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    {{ __('Back to My Rentals') }}
                </a>
            </div>
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
                            <p><span class="font-semibold text-gray-900">Payment Status:</span> {{ ucwords(str_replace('_', ' ', $rental->payment_status)) }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm sm:p-8">
                    @if ($errors->any())
                        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            Please upload at least one valid payment proof file.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.rentals.payment.store', $rental) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        @php
                            $existingProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);
                        @endphp

                        @if (count($existingProofs))
                            <div class="rounded-2xl border border-gray-200 p-4 text-sm text-gray-700">
                                <p class="font-semibold text-gray-900">Current Proof Files</p>
                                <div class="mt-3 flex flex-wrap gap-3">
                                    @foreach ($existingProofs as $proof)
                                        <a href="{{ asset('storage/' . $proof) }}" target="_blank" class="inline-flex rounded-md border border-indigo-200 px-3 py-2 text-indigo-600 hover:bg-indigo-50">
                                            View file {{ $loop->iteration }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="payment_proofs" class="block text-sm font-medium text-gray-700">Payment Proof Files</label>
                            <input
                                id="payment_proofs"
                                name="payment_proofs[]"
                                type="file"
                                multiple
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <p class="mt-1 text-xs text-gray-500">Upload one or more receipt, screenshot, or transfer proof files.</p>
                            @error('payment_proofs')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('payment_proofs.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_note" class="block text-sm font-medium text-gray-700">Payment Note (Optional)</label>
                            <textarea
                                id="payment_note"
                                name="payment_note"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Add any transfer reference or note for the admin."
                            >{{ old('payment_note', $rental->payment_note) }}</textarea>
                            @error('payment_note')
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

    <x-modal name="payment-qr-modal" maxWidth="md">
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Payment QR</h3>
                    <p class="mt-1 text-sm text-gray-600">Scan this QR or use the bank details to complete your transfer.</p>
                </div>

                <button type="button" x-on:click="$dispatch('close-modal', 'payment-qr-modal')" class="rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                    <span class="sr-only">Close</span>
                    &times;
                </button>
            </div>

            <div class="mt-6 rounded-2xl bg-gray-50 p-4">
                <img src="{{ asset('images/payment-qr.svg') }}" alt="GadgetFlow payment QR" class="mx-auto w-full max-w-xs rounded-2xl border border-gray-200 bg-white p-3">
            </div>
        </div>
    </x-modal>
</x-app-layout>
