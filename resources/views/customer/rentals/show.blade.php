<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Rental Details') }}
                </h2>
                <p class="font-body text-sm text-slate">Review your rental information, payment status, QR access, and review details.</p>
            </div>

            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700">
                Back to My Rentals
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
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
                                <x-payment-status-badge :status="$rental->payment_status" :pickup-type="$rental->pickup_type" collected-label="Payment Collected" />
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Type</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">{{ ucfirst($rental->rental_type) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Pickup</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">{{ $rental->pickup_type === 'delivery' ? 'Delivery' : 'Walk-in' }}</p>
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
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Shipping</p>
                                <div class="mt-2">
                                    @if ($rental->pickup_type === 'delivery')
                                        <x-shipping-status-badge :status="$rental->shipping_status" />
                                    @else
                                        <p class="font-body text-sm font-semibold text-ink">-</p>
                                    @endif
                                </div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Total</p>
                                <div class="mt-2"><x-spec-chip>{{ number_format($rental->total_amount, 2) }}</x-spec-chip></div>
                                @if ($rental->points_redeemed > 0)
                                    <p class="mt-2 font-body text-xs text-indigo-700">
                                        {{ number_format($rental->points_redeemed) }} pts redeemed ({{ number_format((float) $rental->discount_amount, 2) }} off)
                                    </p>
                                @endif
                                @if ($rental->status === 'completed')
                                    @php
                                        $earnedTransaction = $rental->loyaltyTransactions->firstWhere('type', 'earned');
                                    @endphp
                                    @if ($earnedTransaction)
                                        <p class="mt-2 font-body text-xs font-semibold text-green-700">
                                            +{{ number_format($earnedTransaction->points) }} points earned
                                        </p>
                                    @endif
                                @endif
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Deposit</p>
                                <div class="mt-2"><x-spec-chip>{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</x-spec-chip></div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Returned At</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">
                                    {{ $rental->returned_at ? $rental->returned_at->format('Y-m-d H:i') : '-' }}
                                </p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-cloud p-4">
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Condition on Return</p>
                                <p class="mt-2 font-body text-sm font-semibold text-ink">
                                    {{ $rental->returned_at ? ucwords(str_replace('_', ' ', $rental->condition_on_return ?? '-')) : '-' }}
                                </p>
                            </div>
                        </div>

                        @if ($rental->status === 'approved' && $rental->pickup_type === 'delivery' && $rental->payment_status === 'pending')
                            <div class="mt-6">
                                <a href="{{ route('customer.rentals.payment.create', $rental) }}" class="inline-flex items-center rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                    Pay Now
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($rental->pickup_type === 'delivery' && $rental->shipping_status !== 'not_applicable')
                    @php
                        $shippingStages = [
                            'waiting_for_shipping' => 'Waiting for Shipping',
                            'shipped' => 'Shipped',
                            'out_for_delivery' => 'Out for Delivery',
                            'delivered' => 'Delivered',
                        ];
                        $shippingStageKeys = array_keys($shippingStages);
                        $currentShippingIndex = array_search($rental->shipping_status, $shippingStageKeys, true);
                    @endphp
                    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Shipping Progress</p>
                        <div class="mt-6 flex items-center">
                            @foreach ($shippingStageKeys as $index => $key)
                                <span class="h-3 w-3 shrink-0 rounded-full {{ $index <= $currentShippingIndex ? 'bg-cyan-500' : 'bg-gray-200' }}"></span>
                                @if (! $loop->last)
                                    <span class="mx-1 h-0.5 flex-1 {{ $index < $currentShippingIndex ? 'bg-cyan-500' : 'bg-gray-200' }}"></span>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-2 flex justify-between gap-2">
                            @foreach ($shippingStages as $key => $label)
                                <span class="flex-1 text-center font-body text-xs {{ array_search($key, $shippingStageKeys, true) === $currentShippingIndex ? 'font-semibold text-cyan-700' : 'text-slate-400' }}">
                                    {{ $label }}
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($rental->pickup_type === 'delivery' && $rental->shipping_status === 'delivered' && ! $rental->returned_at)
                    <section class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-indigo-700">Return Instructions</p>
                        <p class="mt-2 font-body text-sm text-indigo-900">
                            Since this item was received by delivery, please return it yourself via courier or postage, at your own cost, once your rental period ends.
                        </p>

                        <div class="mt-4 rounded-xl bg-white/80 p-4">
                            <p class="font-body text-xs uppercase tracking-wide text-indigo-700">Return Address</p>
                            <p class="mt-1 whitespace-pre-line font-body text-sm font-semibold text-indigo-950">{{ config('company.return_address') }}</p>
                        </div>

                        <p class="mt-4 font-body text-sm text-indigo-900">
                            Once we receive and inspect the returned item, we'll confirm its condition and process your deposit decision (full refund, partial refund, or deduction) — the same process used for walk-in returns.
                        </p>
                    </section>
                @endif

                @if ($rental->pickup_type === 'walk_in' && $rental->payment_status === 'collected' && $rental->payment_collected_at)
                    <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-emerald-700">Payment Receipt</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-emerald-700">Rental Item</p>
                                <p class="mt-1 font-display text-sm font-semibold text-emerald-950">{{ $rental->itemName() }}</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-emerald-700">Rental Period</p>
                                <p class="mt-1 font-body text-sm font-semibold text-emerald-950">
                                    @if ($rental->rental_type === 'hour')
                                        {{ $rental->rental_hours }} hour(s)
                                    @else
                                        {{ $rental->start_date }} to {{ $rental->end_date }}
                                    @endif
                                </p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-emerald-700">Total Amount</p>
                                <div class="mt-1"><x-spec-chip>{{ number_format($rental->total_amount, 2) }}</x-spec-chip></div>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-emerald-700">Deposit Amount</p>
                                <div class="mt-1"><x-spec-chip>{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</x-spec-chip></div>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-emerald-700">Payment Method</p>
                                <p class="mt-1 font-body text-sm font-semibold text-emerald-950">Walk-in / Cash</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-emerald-700">Collected Date</p>
                                <p class="mt-1 font-mono text-sm font-semibold text-emerald-950">{{ $rental->payment_collected_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-body text-xs font-semibold uppercase tracking-wider text-slate">Deposit Details</p>
                            <p class="mt-1 font-body text-sm text-slate-600">Status, refund details, deductions, and any late fee applied.</p>
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold
                            @if ($rental->deposit_status === 'refunded') bg-green-100 text-green-800
                            @elseif ($rental->deposit_status === 'partially_refunded') bg-yellow-100 text-yellow-800
                            @elseif ($rental->deposit_status === 'deducted') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucwords(str_replace('_', ' ', $rental->deposit_status ?? 'held')) }}
                        </span>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl bg-cloud p-4">
                            <p class="font-body text-xs uppercase tracking-wide text-slate">Deposit Amount</p>
                            <div class="mt-1"><x-spec-chip>{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</x-spec-chip></div>
                        </div>
                        @if (!is_null($rental->deposit_refund_amount))
                            <div class="rounded-xl bg-cloud p-4">
                                <p class="font-body text-xs uppercase tracking-wide text-slate">Refunded Amount</p>
                                <div class="mt-1"><x-spec-chip>{{ number_format((float) $rental->deposit_refund_amount, 2) }}</x-spec-chip></div>
                            </div>
                        @endif
                        @if ($rental->deposit_deduction_reason)
                            <div class="rounded-xl bg-cloud p-4 sm:col-span-2">
                                <p class="font-body text-xs uppercase tracking-wide text-slate">Deduction Reason</p>
                                <p class="mt-1 whitespace-pre-line font-body text-sm text-gray-700">{{ $rental->deposit_deduction_reason }}</p>
                            </div>
                        @endif
                        @if ($rental->late_fee_waived)
                            <div class="rounded-xl border border-green-200 bg-green-50 p-4 sm:col-span-2">
                                <p class="font-body text-xs uppercase tracking-wide text-green-700">Late Fee</p>
                                <p class="mt-1 font-body text-sm font-semibold text-green-900">Waived</p>
                            </div>
                        @elseif ((float) ($rental->late_fee_amount ?? 0) > 0)
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 sm:col-span-2">
                                <p class="font-body text-xs uppercase tracking-wide text-amber-700">Late Fee</p>
                                <div class="mt-1"><x-spec-chip>{{ number_format((float) $rental->late_fee_amount, 2) }}</x-spec-chip></div>
                            </div>
                        @endif
                    </div>
                </section>

                @if ($rental->status === 'approved' && !empty($rental->qr_token))
                    <section class="rounded-3xl border border-cyan-200/60 bg-gradient-to-br from-ink via-slate-900 to-cyan-950 p-6 shadow-[0_18px_45px_rgba(34,211,238,0.18)] sm:p-8">
                        <p class="font-body text-xs font-semibold uppercase tracking-wider text-cyan">Pickup / Return QR</p>
                        <p class="mt-2 font-body text-sm text-slate-300">Present this QR code to the admin during pickup or return verification.</p>
                        <div class="mt-5 overflow-hidden rounded-2xl border border-white/10 bg-white shadow-[0_10px_30px_rgba(0,0,0,0.25)] ring-1 ring-cyan/20">
                            <iframe
                                src="{{ route('customer.rentals.qr', $rental) }}"
                                title="Rental QR for {{ $rental->itemName() }}"
                                class="h-[34rem] w-full"
                            ></iframe>
                        </div>
                    </section>
                @endif

                @if ($rental->status === 'completed' && ! $rental->isBundle())
                    @if ($rental->review)
                        <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                            <p class="font-body text-xs font-semibold uppercase tracking-wider text-amber-700">Your Review</p>
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
                    @else
                        <section class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                            <form method="POST" action="{{ route('customer.rentals.review.store', $rental) }}">
                                @csrf
                                <p class="font-body text-xs font-semibold uppercase tracking-wider text-indigo-700">Leave a Review</p>
                                <div class="mt-4">
                                    <label for="rating-{{ $rental->id }}" class="block font-body text-xs font-medium text-indigo-900">Rating</label>
                                    <select id="rating-{{ $rental->id }}" name="rating" class="mt-1 block w-full rounded-md border-indigo-200 font-body text-sm text-gray-700 shadow-sm focus:border-indigo-400 focus:ring-indigo-400" required>
                                        <option value="">Select a rating</option>
                                        @for ($rating = 5; $rating >= 1; $rating--)
                                            <option value="{{ $rating }}" @selected((string) old('rating') === (string) $rating)>
                                                {{ $rating }} star{{ $rating === 1 ? '' : 's' }}
                                            </option>
                                        @endfor
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                                </div>
                                <div class="mt-4">
                                    <label for="comment-{{ $rental->id }}" class="block font-body text-xs font-medium text-indigo-900">Comment</label>
                                    <textarea id="comment-{{ $rental->id }}" name="comment" rows="4" class="mt-1 block w-full rounded-md border-indigo-200 font-body text-sm text-gray-700 shadow-sm focus:border-indigo-400 focus:ring-indigo-400" maxlength="1000" placeholder="Share a few thoughts about the gadget and rental experience.">{{ old('comment') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                                </div>
                                <button type="submit" class="mt-4 inline-flex items-center rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                    Submit Review
                                </button>
                            </form>
                        </section>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
