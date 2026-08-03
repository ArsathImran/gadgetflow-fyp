<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Rental Details') }}
                </h2>
                <p class="font-body text-sm text-slate">Review payment proof, return workflow, and customer feedback for this rental.</p>
            </div>

            <a href="{{ route('admin.rentals.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700">
                Back to Rental Requests
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @php
                    $paymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);
                    $daysOverdue = $rental->daysOverdue();
                    $calculatedLateFee = $daysOverdue * (float) ($rental->isBundle()
                        ? ($rental->bundle?->late_fee_per_day ?? 0)
                        : ($rental->gadget?->late_fee_per_day ?? 0));
                @endphp

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-display text-2xl font-semibold text-ink">{{ $rental->itemName() }}</h3>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                    </span>
                                </div>
                                <p class="mt-2 font-body text-sm text-slate">
                                    {{ $rental->isBundle() ? ($rental->bundle?->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo') : ($rental->gadget?->category?->name ?? '-') }}
                                </p>
                                <p class="mt-2 font-mono text-sm text-slate">Rental ID #{{ $rental->id }}</p>
                                <p class="mt-1 font-body text-sm text-slate">Customer: {{ $rental->user?->name ?? '-' }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
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
                                <x-payment-status-badge :status="$rental->payment_status" :pickup-type="$rental->pickup_type" />
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Pickup</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">{{ $rental->pickup_type === 'delivery' ? 'Delivery' : 'Walk-in' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Rental</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">{{ ucfirst($rental->rental_type) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Dates / Hours</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">
                                    @if ($rental->rental_type === 'hour')
                                        {{ $rental->rental_hours }} hour(s)
                                    @else
                                        {{ $rental->start_date }} to {{ $rental->end_date }}
                                    @endif
                                </p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Total</p>
                                <div class="mt-2"><x-spec-chip>{{ number_format($rental->total_amount, 2) }}</x-spec-chip></div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Shipping</p>
                                <div class="mt-2"><x-shipping-status-badge :status="$rental->shipping_status" /></div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Returned At</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">
                                    {{ $rental->returned_at ? $rental->returned_at->format('Y-m-d H:i') : '-' }}
                                </p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Phone</p>
                                <p class="mt-2 font-mono text-sm font-semibold text-ink">{{ $rental->phone_number ?? '-' }}</p>
                            </div>
                        </div>

                        @if ($rental->pickup_type === 'delivery')
                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                    <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Delivery Address</p>
                                    <p class="mt-2 whitespace-pre-line font-body text-sm text-gray-700">{{ $rental->delivery_address ?? '-' }}</p>
                                </div>
                                <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                    <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">ID Document</p>
                                    <div class="mt-2">
                                        @if ($rental->user?->id_document_path)
                                            <a
                                                href="{{ asset('storage/' . $rental->user->id_document_path) }}"
                                                target="_blank"
                                                rel="noopener"
                                                class="inline-flex items-center gap-1.5 font-body text-sm font-semibold text-indigo transition hover:text-indigo-700"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5h6M9 15.5h6M9 9.5h2M7 20.5h10a2 2 0 0 0 2-2v-11l-5-4H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z" />
                                                </svg>
                                                View ID Document
                                            </a>
                                        @else
                                            <p class="font-body text-sm font-semibold text-ink">No document on file</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-display text-sm font-semibold text-ink">Payment Proofs</p>
                            <p class="mt-1 font-body text-sm text-slate">Uploaded files and payment note.</p>
                        </div>
                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 font-mono text-xs font-semibold text-slate-700">
                            {{ count($paymentProofs) }} file{{ count($paymentProofs) === 1 ? '' : 's' }}
                        </span>
                    </div>

                    <div class="mt-4">
                        @if (count($paymentProofs))
                            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($paymentProofs as $proof)
                                    @php
                                        $extension = strtolower(pathinfo($proof, PATHINFO_EXTENSION));
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
                                    @endphp
                                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-cloud">
                                        @if ($isImage)
                                            <a href="{{ asset('storage/' . $proof) }}" target="_blank" rel="noopener noreferrer" class="block">
                                                <img src="{{ asset('storage/' . $proof) }}" alt="Payment proof {{ $loop->iteration }}" class="h-56 w-full object-cover">
                                            </a>
                                        @else
                                            <div class="flex h-56 items-center justify-center px-4 text-center font-body text-sm text-slate-500">
                                                Preview unavailable for this file type.
                                            </div>
                                        @endif
                                        <div class="flex items-center justify-between gap-3 border-t border-slate-200 px-4 py-3">
                                            <span class="font-body text-sm font-medium text-slate-700">Proof {{ $loop->iteration }}</span>
                                            <a href="{{ asset('storage/' . $proof) }}" target="_blank" rel="noopener noreferrer" class="font-body text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                                                Open file
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="font-body text-sm text-slate-500">No payment proof uploaded.</p>
                        @endif

                        <div class="mt-4 rounded-xl bg-cloud p-4">
                            <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Payment Note</p>
                            <p class="mt-2 whitespace-pre-line font-body text-sm text-slate-700">{{ $rental->payment_note ?: 'No payment note provided.' }}</p>
                        </div>
                    </div>
                </section>

                @if ($rental->payment_status === 'partially_verified')
                    <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-amber-700">Payment Shortfall</p>
                        <p class="mt-2 font-body text-sm text-amber-900">This order was verified with less than the full rental fee and/or deposit collected.</p>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-amber-700">Rental Fee Received</p>
                                <div class="mt-1"><x-spec-chip>{{ number_format((float) ($rental->rental_amount_received ?? 0), 2) }} / {{ number_format($rental->total_amount, 2) }}</x-spec-chip></div>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-amber-700">Deposit Received</p>
                                <div class="mt-1"><x-spec-chip>{{ number_format((float) ($rental->deposit_amount_received ?? 0), 2) }} / {{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</x-spec-chip></div>
                            </div>
                            @if ($rental->payment_shortfall_notes)
                                <div class="rounded-xl bg-white/80 p-4 sm:col-span-2">
                                    <p class="font-body text-xs uppercase tracking-wide text-amber-700">Shortfall Notes</p>
                                    <p class="mt-1 whitespace-pre-line font-body text-sm text-amber-950">{{ $rental->payment_shortfall_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </section>
                @endif

                @if ($rental->pickup_type === 'delivery')
                    <section class="rounded-2xl border border-cyan-200 bg-cyan-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-cyan-700">Shipping Status</p>
                        <p class="mt-2 font-body text-sm text-cyan-900">Update the shipping progress for this delivery order.</p>
                        <form method="POST" action="{{ route('admin.rentals.updateShipping', $rental) }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                <label for="shipping_status" class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-cyan-800">
                                    Shipping Stage
                                </label>
                                <select id="shipping_status" name="shipping_status" class="w-full rounded-md border border-cyan-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-cyan-400 focus:ring-cyan-400" required>
                                    <option value="waiting_for_shipping" @selected($rental->shipping_status === 'waiting_for_shipping')>Waiting for Shipping</option>
                                    <option value="shipped" @selected($rental->shipping_status === 'shipped')>Shipped</option>
                                    <option value="out_for_delivery" @selected($rental->shipping_status === 'out_for_delivery')>Out for Delivery</option>
                                    <option value="delivered" @selected($rental->shipping_status === 'delivered')>Delivered</option>
                                </select>
                            </div>
                            <button type="submit" class="rounded-md border border-cyan-300 bg-white px-4 py-2 font-body text-sm font-semibold text-cyan-700 transition hover:bg-cyan-100">
                                Update Shipping Status
                            </button>
                        </form>
                    </section>
                @endif

                @if ($rental->pickup_type === 'walk_in' && $rental->payment_status === 'pending_collection')
                    <section class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-indigo-700">Walk-in Payment</p>
                        <p class="mt-2 font-body text-sm text-indigo-900">Collect payment at pickup, then confirm it here.</p>
                        <form method="POST" action="{{ route('admin.rentals.payment.collect', $rental) }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-md border border-indigo-300 bg-white px-4 py-2 font-body text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                Mark Payment Collected
                            </button>
                        </form>
                    </section>
                @endif

                @if ($rental->pickup_type === 'walk_in' && $rental->payment_status === 'collected' && $rental->payment_collected_at)
                    <section class="rounded-2xl border border-green-200 bg-green-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-green-700">Walk-in Payment</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-green-700">Collected At</p>
                                <p class="mt-1 font-mono text-sm font-semibold text-green-950">{{ $rental->payment_collected_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-green-700">Collected By</p>
                                <p class="mt-1 font-body text-sm font-semibold text-green-950">{{ $rental->collectedByAdmin?->name ?? '-' }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                @if ($rental->status === 'approved')
                    <section class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm" x-data="{ depositDecision: '{{ old('deposit_decision', 'full_refund') }}' }">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">Return & Deposit</p>
                        <p class="mt-2 font-body text-sm text-indigo-900">Complete the return workflow, deposit decision, and late fee handling.</p>
                        <form method="POST" action="{{ route('admin.rentals.return', $rental) }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                        Condition on Return
                                    </label>
                                    <select name="condition_on_return" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" required>
                                        <option value="good">Good</option>
                                        <option value="damaged">Damaged</option>
                                        <option value="missing_parts">Missing Parts</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                        Deposit Decision
                                    </label>
                                    <select name="deposit_decision" x-model="depositDecision" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" required>
                                        <option value="full_refund">Full Refund</option>
                                        <option value="partial_refund">Partial Refund</option>
                                        <option value="deduct_all">Deduct All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                    Return Notes
                                </label>
                                <textarea name="return_notes" rows="3" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" placeholder="Optional notes about the returned item.">{{ old('return_notes') }}</textarea>
                            </div>
                            <div class="mt-4" x-show="depositDecision === 'partial_refund'">
                                <label class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                    Deposit Refund Amount
                                </label>
                                <input type="number" name="deposit_refund_amount" min="0" max="{{ $rental->deposit_amount ?? 0 }}" step="0.01" value="{{ old('deposit_refund_amount', $rental->deposit_amount) }}" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-mono text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                            <div class="mt-4" x-show="depositDecision === 'partial_refund' || depositDecision === 'deduct_all'">
                                <label class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                    Deduction Reason
                                </label>
                                <textarea name="deposit_deduction_reason" rows="3" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" placeholder="Required when the deposit is not fully refunded.">{{ old('deposit_deduction_reason') }}</textarea>
                            </div>
                            @if ($rental->isOverdue())
                                <div class="mt-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 font-body text-sm text-amber-800">
                                    Late fee: {{ number_format($calculatedLateFee, 2) }} for {{ $daysOverdue }} day{{ $daysOverdue === 1 ? '' : 's' }} overdue
                                </div>
                                <label class="mt-4 inline-flex items-center gap-2 font-body text-sm text-indigo-900">
                                    <input type="checkbox" name="waive_late_fee" value="1" @checked(old('waive_late_fee')) class="rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>Waive late fee</span>
                                </label>
                            @endif
                            <button type="submit" class="mt-4 rounded-md border border-indigo-300 px-4 py-2 font-body text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                Mark as Returned
                            </button>
                        </form>
                    </section>
                @endif

                @if ($rental->review)
                    <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-amber-700">Customer Review</p>
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-sm font-semibold text-amber-900">
                                @for ($star = 1; $star <= 5; $star++)
                                    <span class="{{ $star <= $rental->review->rating ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                @endfor
                            </span>
                            <span class="font-mono text-xs text-amber-800">{{ $rental->review->rating }}/5</span>
                        </div>
                        <p class="mt-3 whitespace-pre-line font-body text-sm text-amber-900">
                            {{ $rental->review->comment ?: 'No written comment provided.' }}
                        </p>
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
