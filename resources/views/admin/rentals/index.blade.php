<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Rental Requests') }}
                </h2>
                <p class="text-sm text-gray-600">Review rental requests, payment proofs, and return details.</p>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rental Item</th>
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
                                            $paymentLabel = $rental->payment_status === 'pending_collection' && $rental->pickup_type === 'walk_in'
                                                ? 'Pending Collection'
                                                : ($rental->payment_status === 'collected' && $rental->pickup_type === 'walk_in'
                                                    ? 'Collected'
                                                    : ucwords(str_replace('_', ' ', $rental->payment_status)));
                                            $paymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);
                                            $daysOverdue = $rental->daysOverdue();
                                        @endphp
                                        <tr id="rental-{{ $rental->id }}">
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $rental->user?->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <div>{{ $rental->phone_number ?? '-' }}</div>
                                                <div class="text-gray-500">{{ $rental->ic_number ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <div class="font-medium text-gray-900">{{ $rental->itemName() }}</div>
                                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                                        {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $rental->isBundle() ? ($rental->bundle?->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo') : ($rental->gadget?->category?->name ?? '-') }}
                                                </div>
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
                                                    @if ($rental->payment_status === 'verified' || $rental->payment_status === 'collected') bg-green-100 text-green-800
                                                    @elseif ($rental->payment_status === 'rejected') bg-red-100 text-red-800
                                                    @elseif ($rental->payment_status === 'pending' || $rental->payment_status === 'pending_collection') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $paymentLabel }}
                                                </span>
                                                @if ($rental->payment_status === 'collected' && $rental->payment_collected_at)
                                                    <div class="mt-2 text-xs text-gray-500">
                                                        Collected: {{ $rental->payment_collected_at->format('Y-m-d H:i') }}
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        By: {{ $rental->collectedByAdmin?->name ?? '-' }}
                                                    </div>
                                                @endif
                                                <div class="mt-2 max-w-xs whitespace-pre-line text-xs text-gray-500">
                                                    {{ $rental->payment_note ?: '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ ucwords(str_replace('_', ' ', $rental->shipping_status)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if (count($paymentProofs))
                                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="inline-flex items-center rounded-md border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                                        View Proof ({{ count($paymentProofs) }})
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
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
                                                            <span class="inline-flex items-center px-1 text-xs text-gray-400">Awaiting proof</span>
                                                        @endif
                                                    @endif

                                                    @if ($rental->status === 'approved')
                                                        <a href="{{ route('admin.scan') }}" class="rounded-md border border-sky-300 px-3 py-2 text-sky-700 transition hover:bg-sky-50">
                                                            Scan QR
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-700 transition hover:bg-slate-50">
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
