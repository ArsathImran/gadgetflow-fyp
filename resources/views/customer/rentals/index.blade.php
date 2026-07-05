<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('My Rentals') }}
                </h2>
                <p class="text-sm text-gray-600">Track your rental requests, payments, and shipping status.</p>
            </div>

            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                {{ __('Browse Gadgets') }}
            </a>
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

                    @if ($rentals->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Gadget</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Pickup</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Dates / Hours</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Shipping</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Returned At</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($rentals as $rental)
                                        @php
                                            $paymentLabel = $rental->payment_status === 'not_required' && $rental->pickup_type === 'walk_in' && $rental->status === 'approved'
                                                ? 'Pay at Store'
                                                : ucwords(str_replace('_', ' ', $rental->payment_status));
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $rental->gadget?->name ?? '-' }}</div>
                                                <div class="text-sm text-gray-500">{{ $rental->gadget?->category?->name ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ ucfirst($rental->rental_type) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $rental->pickup_type === 'delivery' ? 'Delivery' : 'Walk-in' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($rental->rental_type === 'hour')
                                                    {{ $rental->rental_hours }} hour(s)
                                                @else
                                                    {{ $rental->start_date }} to {{ $rental->end_date }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                    @if ($rental->payment_status === 'verified') bg-green-100 text-green-800
                                                    @elseif ($rental->payment_status === 'rejected') bg-red-100 text-red-800
                                                    @elseif ($rental->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $paymentLabel }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ ucwords(str_replace('_', ' ', $rental->shipping_status)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ number_format($rental->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                    @if ($rental->status === 'approved') bg-green-100 text-green-800
                                                    @elseif ($rental->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif ($rental->status === 'returned' || $rental->status === 'completed') bg-blue-100 text-blue-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $rental->status === 'completed' ? 'Completed' : ucfirst($rental->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($rental->returned_at)
                                                    <div>{{ $rental->returned_at->format('Y-m-d H:i') }}</div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{ ucwords(str_replace('_', ' ', $rental->condition_on_return ?? '')) }}
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                @if ($rental->status === 'approved' && $rental->pickup_type === 'delivery' && $rental->payment_status === 'pending')
                                                    <a href="{{ route('customer.rentals.payment.create', $rental) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-white transition hover:bg-indigo-500">
                                                        Pay Now
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="text-base font-semibold text-gray-900">No rental requests yet.</p>
                            <p class="mt-2 text-sm text-gray-500">Start by browsing gadgets and submitting a request.</p>
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
