<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Gadgets') }}
                </h2>
                <p class="mt-1 font-body text-sm text-slate">Manage GadgetFlow gadgets.</p>
            </div>

            <a href="{{ route('gadgets.create') }}" class="inline-flex items-center rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                {{ __('Create Gadget') }}
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

                    <form method="GET" action="{{ route('gadgets.index') }}" class="mb-6 grid gap-3 lg:grid-cols-12">
                        <div class="lg:col-span-5">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search gadgets by name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="lg:col-span-4">
                            <select
                                name="category_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="lg:col-span-3 flex flex-wrap gap-2">
                            <button type="submit" class="inline-flex items-center rounded-md bg-ink px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-slate-900">
                                {{ __('Filter') }}
                            </button>

                            <a href="{{ route('gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-body font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-cloud">
                                <tr>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Image</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Name</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Category</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Rental Price</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Hourly Rental Price</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Deposit</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Quantity</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Status</th>
                                    <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($gadgets as $gadget)
                                    <tr>
                                        <td class="px-6 py-4">
                                            @if ($gadget->image)
                                                <img
                                                    src="{{ asset('storage/' . $gadget->image) }}"
                                                    alt="{{ $gadget->name }}"
                                                    class="h-14 w-14 rounded-lg object-cover border border-gray-200"
                                                >
                                            @else
                                                <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-gray-300 font-body text-xs text-gray-400">
                                                    No image
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-display text-sm font-semibold text-ink">{{ $gadget->name }}</div>
                                            @if ($gadget->brand || $gadget->model)
                                                <div class="mt-1 font-body text-xs text-slate-500">{{ collect([$gadget->brand, $gadget->model])->filter()->implode(' ') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-body text-sm text-slate-600">{{ $gadget->category?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm"><x-spec-chip>{{ number_format($gadget->daily_rental_price, 2) }}</x-spec-chip></td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($gadget->hourly_rental_price !== null)
                                                <x-spec-chip>{{ number_format($gadget->hourly_rental_price, 2) }}</x-spec-chip>
                                            @else
                                                <span class="font-body text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm"><x-spec-chip>{{ number_format($gadget->deposit_amount, 2) }}</x-spec-chip></td>
                                        <td class="px-6 py-4 font-mono text-sm text-slate-600">{{ $gadget->quantity }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($gadget->status === 'active')
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 font-body text-xs font-semibold text-gray-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-body text-sm font-medium">
                                            <div class="inline-flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('gadgets.show', $gadget) }}" class="rounded-md border border-indigo-200 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">View</a>
                                                <a href="{{ route('gadgets.edit', $gadget) }}" class="rounded-md border border-indigo-300 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">Edit</a>
                                                <form action="{{ route('gadgets.destroy', $gadget) }}" method="POST" onsubmit="return confirm('Delete this gadget?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-red-700 transition hover:bg-red-50">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-10 text-center font-body text-sm text-gray-500">
                                            No gadgets found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $gadgets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
