<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Rental Requests') }}
                </h2>
                <p class="text-sm text-gray-600">Review rental requests and payment proofs.</p>
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
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Contact</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Gadget</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Pickup</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rental</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Dates / Hours</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Shipping</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Proof</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($rentals as $rental)
                                        @php
                                            $paymentLabel = $rental->payment_status === 'not_required' && $rental->pickup_type === 'walk_in' && $rental->status === 'approved'
                                                ? 'Pay at Store'
                                                : ucwords(str_replace('_', ' ', $rental->payment_status));
                                            $paymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);
                                            $daysOverdue = $rental->daysOverdue();
                                            $calculatedLateFee = $daysOverdue * (float) ($rental->gadget?->late_fee_per_day ?? 0);
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $rental->user?->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <div>{{ $rental->phone_number ?? '-' }}</div>
                                                <div class="text-gray-500">{{ $rental->ic_number ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $rental->gadget?->name ?? '-' }}</div>
                                                <div class="text-sm text-gray-500">{{ $rental->gadget?->category?->name ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $rental->pickup_type === 'delivery' ? 'Delivery' : 'Walk-in' }}
                                                @if ($rental->pickup_type === 'delivery')
                                                    <div class="mt-1 max-w-xs whitespace-pre-line text-gray-500">{{ $rental->delivery_address ?? '-' }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ ucfirst($rental->rental_type) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($rental->rental_type === 'hour')
                                                    {{ $rental->rental_hours }} hour(s)
                                                @else
                                                    {{ $rental->start_date }} to {{ $rental->end_date }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ number_format($rental->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                    @if ($rental->payment_status === 'verified') bg-green-100 text-green-800
                                                    @elseif ($rental->payment_status === 'rejected') bg-red-100 text-red-800
                                                    @elseif ($rental->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $paymentLabel }}
                                                </span>
                                                <div class="mt-2 max-w-xs whitespace-pre-line text-xs text-gray-500">
                                                    {{ $rental->payment_note ?: '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ ucwords(str_replace('_', ' ', $rental->shipping_status)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if (count($paymentProofs))
                                                    <div class="flex max-w-xs flex-wrap gap-3">
                                                        @foreach ($paymentProofs as $proof)
                                                            @php
                                                                $extension = strtolower(pathinfo($proof, PATHINFO_EXTENSION));
                                                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
                                                            @endphp
                                                            <a href="{{ asset('storage/' . $proof) }}" target="_blank" class="block">
                                                                @if ($isImage)
                                                                    <img src="{{ asset('storage/' . $proof) }}" alt="Payment proof {{ $loop->iteration }}" class="h-16 w-16 rounded-lg border border-gray-200 object-cover">
                                                                @else
                                                                    <span class="inline-flex rounded-md border border-indigo-200 px-3 py-2 text-indigo-600 hover:bg-indigo-50">
                                                                        View file {{ $loop->iteration }}
                                                                    </span>
                                                                @endif
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                    @if ($rental->status === 'approved') bg-green-100 text-green-800
                                                    @elseif ($rental->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif ($rental->status === 'returned' || $rental->status === 'completed') bg-blue-100 text-blue-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $rental->status === 'completed' ? 'Completed' : ucfirst($rental->status) }}
                                                </span>
                                                @if ($rental->isOverdue())
                                                    <div class="mt-2">
                                                        <span class="inline-flex rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-semibold text-orange-800">
                                                            {{ $daysOverdue }} day{{ $daysOverdue === 1 ? '' : 's' }} overdue
                                                        </span>
                                                    </div>
                                                @endif
                                                @if ($rental->returned_at)
                                                    <div class="mt-2 text-xs text-gray-500">
                                                        Returned: {{ $rental->returned_at->format('Y-m-d H:i') }}
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        Condition: {{ ucwords(str_replace('_', ' ', $rental->condition_on_return ?? '-')) }}
                                                    </div>
                                                    <div class="mt-1 max-w-xs whitespace-pre-line text-xs text-gray-500">
                                                        Notes: {{ $rental->return_notes ?: '-' }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <div class="inline-flex flex-wrap justify-end gap-2">
                                                    @if ($rental->status === 'pending')
                                                        <form method="POST" action="{{ route('admin.rentals.approve', $rental) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="rounded-md border border-green-300 px-3 py-2 text-green-700 transition hover:bg-green-50">
                                                                Approve
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('admin.rentals.reject', $rental) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-red-700 transition hover:bg-red-50">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($rental->pickup_type === 'delivery' && $rental->payment_status === 'pending')
                                                        @if (count($paymentProofs))
                                                            <form method="POST" action="{{ route('admin.rentals.payment.verify', $rental) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="rounded-md border border-indigo-300 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">
                                                                    Verify Payment
                                                                </button>
                                                            </form>

                                                            <form method="POST" action="{{ route('admin.rentals.payment.reject', $rental) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-red-700 transition hover:bg-red-50">
                                                                    Reject Payment
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-gray-400">Awaiting proof</span>
                                                        @endif
                                                    @endif

                                                    @if ($rental->status === 'approved')
                                                        <form method="POST" action="{{ route('admin.rentals.return', $rental) }}" class="w-full max-w-xs rounded-md border border-blue-200 bg-blue-50 p-3 text-left" x-data="{ depositDecision: '{{ old('deposit_decision', 'full_refund') }}' }">
                                                            @csrf
                                                            @method('PATCH')
                                                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-blue-800">
                                                                Condition on Return
                                                            </label>
                                                            <select name="condition_on_return" class="w-full rounded-md border border-blue-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-400 focus:ring-blue-400" required>
                                                                <option value="good">Good</option>
                                                                <option value="damaged">Damaged</option>
                                                                <option value="missing_parts">Missing Parts</option>
                                                            </select>
                                                            <label class="mt-3 mb-2 block text-xs font-semibold uppercase tracking-wider text-blue-800">
                                                                Return Notes
                                                            </label>
                                                            <textarea name="return_notes" rows="3" class="w-full rounded-md border border-blue-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-400 focus:ring-blue-400" placeholder="Optional notes about the returned item.">{{ old('return_notes') }}</textarea>
                                                            <label class="mt-3 mb-2 block text-xs font-semibold uppercase tracking-wider text-blue-800">
                                                                Deposit Decision
                                                            </label>
                                                            <select name="deposit_decision" x-model="depositDecision" class="w-full rounded-md border border-blue-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-400 focus:ring-blue-400" required>
                                                                <option value="full_refund">Full Refund</option>
                                                                <option value="partial_refund">Partial Refund</option>
                                                                <option value="deduct_all">Deduct All</option>
                                                            </select>
                                                            <div class="mt-3" x-show="depositDecision === 'partial_refund'">
                                                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-blue-800">
                                                                    Deposit Refund Amount
                                                                </label>
                                                                <input type="number" name="deposit_refund_amount" min="0" max="{{ $rental->deposit_amount ?? 0 }}" step="0.01" value="{{ old('deposit_refund_amount', $rental->deposit_amount) }}" class="w-full rounded-md border border-blue-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-400 focus:ring-blue-400">
                                                            </div>
                                                            <div class="mt-3" x-show="depositDecision === 'partial_refund' || depositDecision === 'deduct_all'">
                                                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-blue-800">
                                                                    Deduction Reason
                                                                </label>
                                                                <textarea name="deposit_deduction_reason" rows="3" class="w-full rounded-md border border-blue-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-400 focus:ring-blue-400" placeholder="Required when the deposit is not fully refunded.">{{ old('deposit_deduction_reason') }}</textarea>
                                                            </div>
                                                            @if ($rental->isOverdue())
                                                                <div class="mt-3 rounded-md border border-orange-200 bg-orange-50 px-3 py-3 text-sm text-orange-800">
                                                                    Late fee: {{ number_format($calculatedLateFee, 2) }} for {{ $daysOverdue }} day{{ $daysOverdue === 1 ? '' : 's' }} overdue
                                                                </div>
                                                                <label class="mt-3 inline-flex items-center gap-2 text-sm text-blue-900">
                                                                    <input type="checkbox" name="waive_late_fee" value="1" @checked(old('waive_late_fee')) class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                                                                    <span>Waive late fee</span>
                                                                </label>
                                                            @endif
                                                            <button type="submit" class="mt-3 rounded-md border border-blue-300 px-3 py-2 text-blue-700 transition hover:bg-blue-100">
                                                                Mark as Returned
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="text-base font-semibold text-gray-900">No rental requests found.</p>
                        </div>
                    @endif

                    <div class="mt-6">
                        {{ $rentals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
