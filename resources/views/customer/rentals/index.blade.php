<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('My Rentals') }}
                </h2>
                <p class="font-body text-sm text-slate">Track your rental requests, payments, and shipping status.</p>
            </div>

            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700">
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

                    @if (session('error'))
                        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($rentals->count())
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-ink">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Rental Item</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Pickup</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Total</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Status</th>
                                        <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach ($rentals as $rental)
                                        @php
                                            $itemThumbnail = $rental->isBundle() ? $rental->bundle?->image : $rental->gadget?->image;
                                        @endphp
                                        <tr class="transition hover:bg-slate-50">
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
                                                        <div class="font-body text-sm text-slate">
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
                                                @if ($rental->points_redeemed > 0)
                                                    <div class="mt-2">
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2.5 py-0.5 font-mono text-xs font-semibold text-indigo-700">
                                                            -{{ number_format($rental->points_redeemed) }} pts ({{ number_format((float) $rental->discount_amount, 2) }} off)
                                                        </span>
                                                    </div>
                                                @endif
                                                @if ($rental->status === 'completed')
                                                    @php
                                                        $earnedTransaction = $rental->loyaltyTransactions->firstWhere('type', 'earned');
                                                    @endphp
                                                    @if ($earnedTransaction)
                                                        <div class="mt-2">
                                                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 font-mono text-xs font-semibold text-green-700">
                                                                +{{ number_format($earnedTransaction->points) }} points earned
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif
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
                                                @if ($rental->status === 'pending')
                                                    <form method="POST" action="{{ route('customer.rentals.cancel', $rental) }}" class="mt-3" onsubmit="return confirm('Are you sure you want to cancel this rental request?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-md border border-indigo-200 px-3 py-2 text-xs font-body font-semibold text-indigo-700 transition hover:bg-indigo-50">
                                                            <x-icon-x-circle class="h-3.5 w-3.5" />
                                                            Cancel Request
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($rental->isOverdue())
                                                    <div class="mt-2">
                                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 font-body text-xs font-semibold text-amber-800">
                                                            {{ $rental->daysOverdue() }} day{{ $rental->daysOverdue() === 1 ? '' : 's' }} overdue
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <div class="inline-flex flex-col items-end gap-2">
                                                    @if ($rental->status === 'approved' && $rental->pickup_type === 'delivery' && $rental->payment_status === 'pending')
                                                        <a href="{{ route('customer.rentals.payment.create', $rental) }}" class="inline-flex items-center gap-1.5 rounded-md bg-indigo px-3 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                                            <x-icon-wallet class="h-3.5 w-3.5" />
                                                            Pay Now
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('customer.rentals.show', $rental) }}" class="inline-flex items-center gap-1.5 rounded-md border border-indigo-200 bg-white px-3 py-2 text-sm font-body font-semibold text-indigo-700 transition hover:bg-indigo-50">
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
                            <p class="font-display text-base font-semibold text-ink">No rental requests yet.</p>
                            <p class="mt-2 font-body text-sm text-gray-500">Start by browsing gadgets and submitting a request.</p>
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
