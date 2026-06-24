<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Rental Requests') }}
                </h2>
                <p class="text-sm text-gray-600">Review and manage rental requests.</p>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Gadget</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
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
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $rental->gadget?->name ?? '-' }}</div>
                                                <div class="text-sm text-gray-500">{{ $rental->gadget?->category?->name ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $rental->start_date }} to {{ $rental->end_date }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ number_format($rental->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                    @if ($rental->status === 'approved') bg-green-100 text-green-800
                                                    @elseif ($rental->status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($rental->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                @if ($rental->status === 'pending')
                                                    <div class="inline-flex flex-wrap justify-end gap-2">
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
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">No actions</span>
                                                @endif
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
