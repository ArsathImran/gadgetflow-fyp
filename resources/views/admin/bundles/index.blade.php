<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Bundles') }}
                </h2>
                <p class="mt-1 font-body text-sm text-slate">Create and manage themed rental packages built from existing gadgets.</p>
            </div>

            <a href="{{ route('admin.bundles.create') }}" class="inline-flex items-center rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                {{ __('Create Bundle') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-ink">
                                <tr>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Image</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Name</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Type</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Daily Rental Price</th>
                                    <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Status</th>
                                    <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($bundles as $bundle)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            @if ($bundle->image)
                                                <img
                                                    src="{{ asset('storage/' . $bundle->image) }}"
                                                    alt="{{ $bundle->name }}"
                                                    class="h-14 w-14 rounded-lg border border-gray-200 object-cover"
                                                >
                                            @else
                                                <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-gray-300 font-body text-xs text-gray-400">
                                                    No image
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-display text-sm font-semibold text-ink">{{ $bundle->name }}</div>
                                            @if ($bundle->description)
                                                <div class="mt-1 max-w-xs truncate font-body text-xs text-slate-500" title="{{ $bundle->description }}">
                                                    {{ \Illuminate\Support\Str::limit($bundle->description, 80) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {{ $bundle->type === 'wedding' ? 'bg-pink-100 text-pink-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                {{ $bundle->type === 'wedding' ? 'Wedding' : 'Short Film' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($bundle->daily_rental_price !== null)
                                                <x-spec-chip>{{ number_format($bundle->daily_rental_price, 2) }}</x-spec-chip>
                                            @else
                                                <span class="font-body text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($bundle->status === 'active')
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 font-body text-xs font-semibold text-gray-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-body text-sm font-medium">
                                            <div class="inline-flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('admin.bundles.edit', $bundle) }}" class="inline-flex items-center gap-1.5 rounded-md border border-indigo-300 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">
                                                    <x-icon-pencil class="h-3.5 w-3.5" />
                                                    Edit
                                                </a>
                                                <button
                                                    type="button"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-bundle-delete-{{ $bundle->id }}')"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-red-300 px-3 py-2 text-red-700 transition hover:bg-red-50"
                                                >
                                                    <x-icon-trash class="h-3.5 w-3.5" />
                                                    Delete
                                                </button>

                                                <x-modal name="confirm-bundle-delete-{{ $bundle->id }}" focusable>
                                                    <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" class="p-6">
                                                        @csrf
                                                        @method('DELETE')

                                                        <h2 class="font-display text-lg font-semibold text-ink">
                                                            {{ __('Delete this bundle?') }}
                                                        </h2>

                                                        <p class="mt-1 font-body text-sm text-slate">
                                                            {{ __('This will permanently delete ":name" and cannot be undone.', ['name' => $bundle->name]) }}
                                                        </p>

                                                        <div class="mt-6 flex justify-end">
                                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                                {{ __('Cancel') }}
                                                            </x-secondary-button>

                                                            <x-danger-button class="ms-3">
                                                                {{ __('Delete') }}
                                                            </x-danger-button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center font-body text-sm text-gray-500">
                                            No bundles found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $bundles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
