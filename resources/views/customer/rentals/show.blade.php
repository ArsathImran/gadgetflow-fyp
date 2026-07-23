<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Rental Details') }}
                </h2>
                <p class="text-sm text-gray-600">Review your rental information, payment status, QR access, and review details.</p>
            </div>

            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                Back to My Rentals
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @if (session('success'))
                    <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @php
                    $paymentLabel = $rental->payment_status === 'pending_collection' && $rental->pickup_type === 'walk_in'
                        ? 'Pending Collection'
                        : ($rental->payment_status === 'collected' && $rental->pickup_type === 'walk_in'
                            ? 'Payment Collected'
                            : ucwords(str_replace('_', ' ', $rental->payment_status)));
                @endphp

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-2xl font-semibold text-gray-900">{{ $rental->itemName() }}</h3>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ $rental->isBundle() ? ($rental->bundle?->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo') : ($rental->gadget?->category?->name ?? '-') }}
                                </p>
                                <p class="mt-2 text-sm text-gray-500">Rental ID #{{ $rental->id }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
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
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if ($rental->payment_status === 'verified' || $rental->payment_status === 'collected') bg-green-100 text-green-800
                                    @elseif ($rental->payment_status === 'rejected') bg-red-100 text-red-800
                                    @elseif ($rental->payment_status === 'pending' || $rental->payment_status === 'pending_collection') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $paymentLabel }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Type</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">{{ ucfirst($rental->rental_type) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pickup</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">{{ $rental->pickup_type === 'delivery' ? 'Delivery' : 'Walk-in' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Dates / Hours</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">
                                    @if ($rental->rental_type === 'hour')
                                        {{ $rental->rental_hours }} hour(s)
                                    @else
                                        {{ $rental->start_date }} to {{ $rental->end_date }}
                                    @endif
                                </p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Shipping</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $rental->shipping_status)) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">{{ number_format($rental->total_amount, 2) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Deposit</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Returned At</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">
                                    {{ $rental->returned_at ? $rental->returned_at->format('Y-m-d H:i') : '-' }}
                                </p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Condition on Return</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">
                                    {{ $rental->returned_at ? ucwords(str_replace('_', ' ', $rental->condition_on_return ?? '-')) : '-' }}
                                </p>
                            </div>
                        </div>

                        @if ($rental->status === 'approved' && $rental->pickup_type === 'delivery' && $rental->payment_status === 'pending')
                            <div class="mt-6">
                                <a href="{{ route('customer.rentals.payment.create', $rental) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500">
                                    Pay Now
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($rental->pickup_type === 'walk_in' && $rental->payment_status === 'collected' && $rental->payment_collected_at)
                    <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Payment Receipt</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="text-xs uppercase tracking-wide text-emerald-700">Rental Item</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-950">{{ $rental->itemName() }}</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="text-xs uppercase tracking-wide text-emerald-700">Rental Period</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-950">
                                    @if ($rental->rental_type === 'hour')
                                        {{ $rental->rental_hours }} hour(s)
                                    @else
                                        {{ $rental->start_date }} to {{ $rental->end_date }}
                                    @endif
                                </p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="text-xs uppercase tracking-wide text-emerald-700">Total Amount</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-950">{{ number_format($rental->total_amount, 2) }}</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="text-xs uppercase tracking-wide text-emerald-700">Deposit Amount</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-950">{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="text-xs uppercase tracking-wide text-emerald-700">Payment Method</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-950">Walk-in / Cash</p>
                            </div>
                            <div class="rounded-xl bg-white/80 p-4">
                                <p class="text-xs uppercase tracking-wide text-emerald-700">Collected Date</p>
                                <p class="mt-1 text-sm font-semibold text-emerald-950">{{ $rental->payment_collected_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Deposit Details</p>
                            <p class="mt-1 text-sm text-gray-600">Status, refund details, deductions, and any late fee applied.</p>
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                            @if ($rental->deposit_status === 'refunded') bg-green-100 text-green-800
                            @elseif ($rental->deposit_status === 'partially_refunded') bg-yellow-100 text-yellow-800
                            @elseif ($rental->deposit_status === 'deducted') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucwords(str_replace('_', ' ', $rental->deposit_status ?? 'held')) }}
                        </span>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Deposit Amount</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</p>
                        </div>
                        @if (!is_null($rental->deposit_refund_amount))
                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Refunded Amount</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format((float) $rental->deposit_refund_amount, 2) }}</p>
                            </div>
                        @endif
                        @if ($rental->deposit_deduction_reason)
                            <div class="rounded-xl bg-gray-50 p-4 sm:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Deduction Reason</p>
                                <p class="mt-1 whitespace-pre-line text-sm text-gray-700">{{ $rental->deposit_deduction_reason }}</p>
                            </div>
                        @endif
                        @if ($rental->late_fee_waived)
                            <div class="rounded-xl border border-green-200 bg-green-50 p-4 sm:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-green-700">Late Fee</p>
                                <p class="mt-1 text-sm font-semibold text-green-900">Waived</p>
                            </div>
                        @elseif ((float) ($rental->late_fee_amount ?? 0) > 0)
                            <div class="rounded-xl border border-orange-200 bg-orange-50 p-4 sm:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-orange-700">Late Fee</p>
                                <p class="mt-1 text-sm font-semibold text-orange-900">{{ number_format((float) $rental->late_fee_amount, 2) }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                @if ($rental->status === 'approved' && !empty($rental->qr_token))
                    <section class="rounded-2xl border border-sky-200 bg-sky-50 p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-sky-700">Pickup / Return QR</p>
                        <p class="mt-2 text-sm text-sky-900">Present this QR code to the admin during pickup or return verification.</p>
                        <div class="mt-4 overflow-hidden rounded-2xl border border-sky-100 bg-white">
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
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Your Review</p>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="text-sm font-semibold text-amber-900">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span class="{{ $star <= $rental->review->rating ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                    @endfor
                                </span>
                                <span class="text-xs text-amber-800">{{ $rental->review->rating }}/5</span>
                            </div>
                            <p class="mt-3 whitespace-pre-line text-sm text-amber-900">
                                {{ $rental->review->comment ?: 'No written comment provided.' }}
                            </p>
                        </section>
                    @else
                        <section class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                            <form method="POST" action="{{ route('customer.rentals.review.store', $rental) }}">
                                @csrf
                                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-700">Leave a Review</p>
                                <div class="mt-4">
                                    <label for="rating-{{ $rental->id }}" class="block text-xs font-medium text-indigo-900">Rating</label>
                                    <select id="rating-{{ $rental->id }}" name="rating" class="mt-1 block w-full rounded-md border-indigo-200 text-sm text-gray-700 shadow-sm focus:border-indigo-400 focus:ring-indigo-400" required>
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
                                    <label for="comment-{{ $rental->id }}" class="block text-xs font-medium text-indigo-900">Comment</label>
                                    <textarea id="comment-{{ $rental->id }}" name="comment" rows="4" class="mt-1 block w-full rounded-md border-indigo-200 text-sm text-gray-700 shadow-sm focus:border-indigo-400 focus:ring-indigo-400" maxlength="1000" placeholder="Share a few thoughts about the gadget and rental experience.">{{ old('comment') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                                </div>
                                <button type="submit" class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500">
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
