<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ $user->name }}
                </h2>
                <p class="mt-1 font-body text-sm text-slate">Customer rental history and account status.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700">
                    {{ __('Back') }}
                </a>

                <form method="POST" action="{{ route('admin.customers.toggleBlock', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center rounded-md {{ $user->is_blocked ? 'bg-green-600 hover:bg-green-500' : 'bg-red-600 hover:bg-red-500' }} px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition">
                        {{ $user->is_blocked ? 'Unblock Customer' : 'Block Customer' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid gap-4 sm:grid-cols-4">
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">Name</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->name }}</p>
                        </div>
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">Email</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->email }}</p>
                        </div>
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">Joined</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->created_at->format('Y-m-d') }}</p>
                        </div>
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">Status</p>
                            <div class="mt-2">
                                @if ($user->is_blocked)
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 font-body text-xs font-semibold text-red-800">Blocked</span>
                                @else
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">Active</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-4">
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">Phone</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->phone ?: '—' }}</p>
                        </div>
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">City</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->city ?: '—' }}</p>
                        </div>
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">State</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->state ?: '—' }}</p>
                        </div>
                        <div class="rounded-2xl bg-cloud p-4">
                            <p class="font-body text-sm text-slate">Address</p>
                            <p class="mt-1 font-body text-base font-semibold text-ink">{{ $user->address ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-4">
                        <div class="rounded-2xl bg-cloud p-4 sm:col-span-2">
                            <p class="font-body text-sm text-slate">Supporting Document</p>
                            @if ($user->id_document_path)
                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">Uploaded</span>
                                    <span class="font-body text-sm text-slate-600">{{ $user->id_document_uploaded_at?->format('Y-m-d') }}</span>
                                    <a
                                        href="{{ Storage::url($user->id_document_path) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="font-body text-sm font-semibold text-indigo transition hover:text-indigo-700"
                                    >
                                        View Document
                                    </a>
                                </div>
                            @else
                                <div class="mt-2">
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 font-body text-xs font-semibold text-amber-800">Not Uploaded</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-display text-lg font-semibold text-ink">Rental History</h3>
                    <p class="mt-1 font-body text-sm text-slate">All rentals made by this customer.</p>

                    <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-cloud">
                                <tr>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Rental Item</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Dates / Hours</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Total</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Payment</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Deposit</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Status</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Returned At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($rentals as $rental)
                                    @php
                                        $paymentLabel = $rental->payment_status === 'pending_collection' && $rental->pickup_type === 'walk_in'
                                            ? 'Pending Collection'
                                            : ($rental->payment_status === 'collected' && $rental->pickup_type === 'walk_in'
                                                ? 'Collected'
                                                : ucwords(str_replace('_', ' ', $rental->payment_status)));
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="font-display font-semibold text-ink">{{ $rental->itemName() }}</div>
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                                    {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-body text-sm text-slate-600">
                                            @if ($rental->rental_type === 'hour')
                                                {{ $rental->rental_hours }} hour(s)
                                            @else
                                                {{ $rental->start_date }} to {{ $rental->end_date }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm"><x-spec-chip>{{ number_format($rental->total_amount, 2) }}</x-spec-chip></td>
                                        <td class="px-6 py-4 font-body text-sm text-slate-600">
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold
                                                @if ($rental->payment_status === 'verified' || $rental->payment_status === 'collected') bg-green-100 text-green-800
                                                @elseif ($rental->payment_status === 'rejected') bg-red-100 text-red-800
                                                @elseif ($rental->payment_status === 'pending' || $rental->payment_status === 'pending_collection') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $paymentLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <x-spec-chip>{{ number_format((float) ($rental->deposit_amount ?? 0), 2) }}</x-spec-chip>
                                            <div class="mt-2">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold
                                                    @if ($rental->deposit_status === 'refunded') bg-green-100 text-green-800
                                                    @elseif ($rental->deposit_status === 'partially_refunded') bg-yellow-100 text-yellow-800
                                                    @elseif ($rental->deposit_status === 'deducted') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucwords(str_replace('_', ' ', $rental->deposit_status ?? 'held')) }}
                                                </span>
                                            </div>
                                            @if ((float) ($rental->late_fee_amount ?? 0) > 0)
                                                <div class="mt-2 font-body text-xs text-amber-700">
                                                    Late Fee: {{ number_format((float) $rental->late_fee_amount, 2) }}
                                                </div>
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
                                        </td>
                                        <td class="px-6 py-4 font-body text-sm text-slate-600">
                                            {{ $rental->returned_at?->format('Y-m-d H:i') ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center font-body text-sm text-gray-500">
                                            No rentals found for this customer.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $rentals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
