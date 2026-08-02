<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Rental Requests') }}
                </h2>
                <p class="font-body text-sm text-slate">Review rental requests, payment proofs, and return details.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($rentals->count())
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-cloud">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Customer</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Rental Item</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Pickup</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Total</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Status</th>
                                        <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach ($rentals as $rental)
                                        @php
                                            $paymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);
                                            $daysOverdue = $rental->daysOverdue();
                                            $itemThumbnail = $rental->isBundle() ? $rental->bundle?->image : $rental->gadget?->image;
                                        @endphp
                                        <tr id="rental-{{ $rental->id }}" class="transition hover:bg-slate-50">
                                            <td class="px-6 py-4 font-body text-sm text-slate-600">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-sm font-semibold text-white">
                                                        {{ strtoupper(substr($rental->user?->name ?? '?', 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <div class="font-medium text-ink">{{ $rental->user?->name ?? '-' }}</div>
                                                        <div class="text-xs text-slate-500">{{ $rental->phone_number ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-cloud ring-1 ring-slate-200">
                                                        @if ($itemThumbnail)
                                                            <img src="{{ asset('storage/' . $itemThumbnail) }}" alt="{{ $rental->itemName() }}" class="h-full w-full object-cover">
                                                        @else
                                                            <svg class="h-5 w-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 12 4l8.25 4.25M3.75 8.25v8.5L12 21l8.25-4.25v-8.5M3.75 8.25 12 12.5l8.25-4.25M12 12.5V21" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <div class="font-display font-semibold text-ink">{{ $rental->itemName() }}</div>
                                                            <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                                                {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                                            </span>
                                                        </div>
                                                        <div class="font-body text-sm text-slate-500">
                                                            {{ $rental->isBundle() ? ($rental->bundle?->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo') : ($rental->gadget?->category?->name ?? '-') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-body text-sm text-slate-600">
                                                {{ $rental->pickup_type === 'delivery' ? 'Delivery' : 'Walk-in' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <x-spec-chip>{{ number_format($rental->total_amount, 2) }}</x-spec-chip>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold
                                                    @if ($rental->status === 'approved') bg-green-100 text-green-800
                                                    @elseif ($rental->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif ($rental->status === 'cancelled_by_customer') bg-rose-100 text-rose-800
                                                    @elseif ($rental->status === 'returned' || $rental->status === 'completed') bg-blue-100 text-blue-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{
                                                        match ($rental->status) {
                                                            'completed' => 'Completed',
                                                            'cancelled_by_customer' => 'Cancelled by Customer',
                                                            default => ucfirst($rental->status),
                                                        }
                                                    }}
                                                </span>
                                                @if ($rental->isOverdue())
                                                    <div class="mt-2">
                                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 font-body text-xs font-semibold text-amber-800">
                                                            {{ $daysOverdue }} day{{ $daysOverdue === 1 ? '' : 's' }} overdue
                                                        </span>
                                                    </div>
                                                @endif
                                                @if ($rental->returned_at)
                                                    <div class="mt-2 font-body text-xs text-slate-500">
                                                        Returned: {{ $rental->returned_at->format('Y-m-d H:i') }}
                                                    </div>
                                                    <div class="mt-1 font-body text-xs text-slate-500">
                                                        Condition: {{ ucwords(str_replace('_', ' ', $rental->condition_on_return ?? '-')) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right font-body text-sm font-medium">
                                                <div class="inline-flex flex-wrap justify-end gap-2">
                                                    @if ($rental->status === 'pending')
                                                        <form method="POST" action="{{ route('admin.rentals.approve', $rental) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-md border border-green-300 px-3 py-2 font-body text-green-700 transition hover:bg-green-50">
                                                                <x-icon-check class="h-3.5 w-3.5" />
                                                                Approve
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('admin.rentals.reject', $rental) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-md border border-red-300 px-3 py-2 font-body text-red-700 transition hover:bg-red-50">
                                                                <x-icon-x class="h-3.5 w-3.5" />
                                                                Reject
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($rental->pickup_type === 'delivery' && $rental->payment_status === 'pending')
                                                        @if (count($paymentProofs))
                                                            <button
                                                                type="button"
                                                                x-data
                                                                x-on:click="$dispatch('open-modal', 'verify-payment-{{ $rental->id }}')"
                                                                class="inline-flex items-center gap-1.5 rounded-md border border-indigo-300 px-3 py-2 font-body text-indigo-700 transition hover:bg-indigo-50"
                                                            >
                                                                <x-icon-wallet class="h-3.5 w-3.5" />
                                                                Verify Payment
                                                            </button>

                                                            <form method="POST" action="{{ route('admin.rentals.payment.reject', $rental) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md border border-red-300 px-3 py-2 font-body text-red-700 transition hover:bg-red-50">
                                                                    <x-icon-x class="h-3.5 w-3.5" />
                                                                    Reject Payment
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="inline-flex items-center px-1 font-body text-xs text-slate-400">Awaiting proof</span>
                                                        @endif
                                                    @endif

                                                    @if ($rental->status === 'approved')
                                                        <a href="{{ route('admin.scan') }}" class="inline-flex items-center gap-1.5 rounded-md border border-cyan-300 px-3 py-2 font-body text-cyan-700 transition hover:bg-cyan-50">
                                                            <x-icon-qr class="h-3.5 w-3.5" />
                                                            Scan QR
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="inline-flex items-center gap-1.5 rounded-md border border-indigo-200 bg-white px-3 py-2 font-body text-indigo-700 transition hover:bg-indigo-50">
                                                        <x-icon-eye class="h-3.5 w-3.5" />
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="font-display text-base font-semibold text-ink">No rental requests found.</p>
                        </div>
                    @endif

                    <div class="mt-6">
                        {{ $rentals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($rentals as $rental)
        @php
            $modalPaymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);
        @endphp
        @if ($rental->pickup_type === 'delivery' && $rental->payment_status === 'pending' && count($modalPaymentProofs))
            <x-modal name="verify-payment-{{ $rental->id }}" maxWidth="lg">
                <div
                    class="p-6"
                    x-data="{
                        rentalPaidFull: true,
                        depositPaidFull: true,
                        rentalAmountReceived: '',
                        depositAmountReceived: '',
                        totalAmount: {{ (float) $rental->total_amount }},
                        depositAmount: {{ (float) ($rental->deposit_amount ?? 0) }},
                        get rentalShortfall() {
                            const received = parseFloat(this.rentalAmountReceived);
                            return isNaN(received) ? 0 : Math.max(0, this.totalAmount - received);
                        },
                        get depositShortfall() {
                            const received = parseFloat(this.depositAmountReceived);
                            return isNaN(received) ? 0 : Math.max(0, this.depositAmount - received);
                        },
                    }"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-display text-lg font-semibold text-ink">Verify Payment</h3>
                            <p class="mt-1 font-body text-sm text-slate">Confirm how much of the rental fee and deposit were received for this order.</p>
                        </div>
                        <button type="button" x-on:click="$dispatch('close-modal', 'verify-payment-{{ $rental->id }}')" class="rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <span class="sr-only">Close</span>
                            &times;
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.rentals.payment.verify', $rental) }}" class="mt-6 space-y-5">
                        @csrf
                        @method('PATCH')

                        <div class="rounded-2xl border border-gray-200 p-4">
                            <label class="inline-flex items-start gap-3 font-body text-sm text-gray-700">
                                <input type="checkbox" name="rental_paid_full" value="1" x-model="rentalPaidFull" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>Rental fee received in full (RM {{ number_format($rental->total_amount, 2) }})</span>
                            </label>
                            <div class="mt-3" x-show="! rentalPaidFull" x-cloak>
                                <label class="block font-body text-xs font-medium text-gray-700">Amount Received (RM)</label>
                                <input
                                    type="number"
                                    name="rental_amount_received"
                                    step="0.01"
                                    min="0"
                                    max="{{ (float) $rental->total_amount }}"
                                    x-model.number="rentalAmountReceived"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00"
                                >
                                <p class="mt-1 font-mono text-xs font-semibold text-amber-700" x-show="rentalShortfall > 0">
                                    RM <span x-text="rentalShortfall.toFixed(2)"></span> short
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-4">
                            <label class="inline-flex items-start gap-3 font-body text-sm text-gray-700">
                                <input type="checkbox" name="deposit_paid_full" value="1" x-model="depositPaidFull" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>Deposit received in full (RM {{ number_format((float) ($rental->deposit_amount ?? 0), 2) }})</span>
                            </label>
                            <div class="mt-3" x-show="! depositPaidFull" x-cloak>
                                <label class="block font-body text-xs font-medium text-gray-700">Amount Received (RM)</label>
                                <input
                                    type="number"
                                    name="deposit_amount_received"
                                    step="0.01"
                                    min="0"
                                    max="{{ (float) ($rental->deposit_amount ?? 0) }}"
                                    x-model.number="depositAmountReceived"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00"
                                >
                                <p class="mt-1 font-mono text-xs font-semibold text-amber-700" x-show="depositShortfall > 0">
                                    RM <span x-text="depositShortfall.toFixed(2)"></span> short
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block font-body text-xs font-medium text-gray-700">Notes (optional)</label>
                            <textarea
                                name="shortfall_notes"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Explain the shortfall or any payment arrangement."
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" x-on:click="$dispatch('close-modal', 'verify-payment-{{ $rental->id }}')" class="rounded-md border border-gray-300 bg-white px-4 py-2 font-body text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="rounded-md bg-indigo px-4 py-2 font-body text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                Confirm Verification
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
        @endif
    @endforeach
</x-app-layout>
