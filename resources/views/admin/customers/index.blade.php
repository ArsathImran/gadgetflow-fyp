<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Customers') }}
                </h2>
                <p class="mt-1 font-body text-sm text-slate">Manage customer accounts and review their activity.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-6 grid gap-3 lg:grid-cols-12">
                        <div class="lg:col-span-9">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search customers by name or email"
                                class="block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="lg:col-span-3 flex flex-wrap gap-2">
                            <button type="submit" class="inline-flex items-center rounded-md bg-ink px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-slate-900">
                                {{ __('Filter') }}
                            </button>

                            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-body font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>

                    {{-- Desktop / tablet: table layout --}}
                    <div class="hidden md:block overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-cloud">
                                <tr>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Customer</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Joined</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Rentals</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Total Spent</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Status</th>
                                    <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-display text-sm font-semibold text-ink">{{ $customer->name }}</div>
                                            <div class="mt-1 font-body text-sm text-slate-500">{{ $customer->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-body text-sm text-slate-600">{{ $customer->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 font-mono text-sm text-slate-600">{{ $customer->rentals_count }}</td>
                                        <td class="px-6 py-4 text-sm"><x-spec-chip>{{ number_format((float) ($customer->rentals_sum_total_amount ?? 0), 2) }}</x-spec-chip></td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($customer->is_blocked)
                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 font-body text-xs font-semibold text-red-800">Blocked</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">Active</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-body text-sm font-medium">
                                            <div class="inline-flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('admin.customers.show', $customer) }}" class="rounded-md border border-indigo-200 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">View</a>
                                                <form method="POST" action="{{ route('admin.customers.toggleBlock', $customer) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="rounded-md border {{ $customer->is_blocked ? 'border-green-300 text-green-700 hover:bg-green-50' : 'border-red-300 text-red-700 hover:bg-red-50' }} px-3 py-2 transition">
                                                        {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center font-body text-sm text-gray-500">
                                            No customers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile: stacked card layout --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($customers as $customer)
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="font-display text-sm font-semibold text-ink">{{ $customer->name }}</div>
                                <div class="mt-1 font-body text-sm text-slate-500">{{ $customer->email }}</div>

                                <div class="mt-4 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                                    <div>
                                        <p class="font-body text-xs uppercase tracking-wide text-slate">Joined</p>
                                        <p class="mt-1 font-body text-sm text-slate-600">{{ $customer->created_at->format('Y-m-d') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-body text-xs uppercase tracking-wide text-slate">Rentals</p>
                                        <p class="mt-1 font-mono text-sm text-slate-600">{{ $customer->rentals_count }}</p>
                                    </div>
                                    <div>
                                        <p class="font-body text-xs uppercase tracking-wide text-slate">Total Spent</p>
                                        <div class="mt-1 text-sm"><x-spec-chip>{{ number_format((float) ($customer->rentals_sum_total_amount ?? 0), 2) }}</x-spec-chip></div>
                                    </div>
                                    <div>
                                        <p class="font-body text-xs uppercase tracking-wide text-slate">Status</p>
                                        <div class="mt-1">
                                            @if ($customer->is_blocked)
                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 font-body text-xs font-semibold text-red-800">Blocked</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">Active</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="rounded-md border border-indigo-200 px-3 py-2 text-sm text-indigo-700 transition hover:bg-indigo-50">View</a>
                                    <form method="POST" action="{{ route('admin.customers.toggleBlock', $customer) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md border {{ $customer->is_blocked ? 'border-green-300 text-green-700 hover:bg-green-50' : 'border-red-300 text-red-700 hover:bg-red-50' }} px-3 py-2 text-sm transition">
                                            {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-gray-300 px-4 py-10 text-center font-body text-sm text-gray-500">
                                No customers found.
                            </p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
