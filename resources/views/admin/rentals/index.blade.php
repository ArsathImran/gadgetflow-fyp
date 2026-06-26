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
                                                    {{ ucwords(str_replace('_', ' ', $rental->payment_status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ ucwords(str_replace('_', ' ', $rental->shipping_status)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($rental->payment_proof)
                                                    <a href="{{ asset('storage/' . $rental->payment_proof) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                                        View Proof
                                                    </a>
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
                                                    {{ ucfirst($rental->status) }}
                                                </span>
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

                                                    @if ($rental->payment_status === 'pending')
                                                        @if ($rental->payment_proof)
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
