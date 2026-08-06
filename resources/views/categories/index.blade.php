<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Categories') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Manage GadgetFlow categories.</p>
            </div>

            <a href="{{ route('categories.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                {{ __('Create Category') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('categories.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="flex-1">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search categories by name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-700">
                                {{ __('Search') }}
                            </button>

                            <a href="{{ route('categories.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>

                    {{-- Desktop / tablet: table layout --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Description</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($category->status === 'active')
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $category->description ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <div class="inline-flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('categories.show', $category) }}" class="rounded-md border border-gray-300 px-3 py-2 text-gray-700 transition hover:bg-gray-50">View</a>
                                                <a href="{{ route('categories.edit', $category) }}" class="rounded-md border border-indigo-300 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">Edit</a>
                                                <button
                                                    type="button"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-category-delete-{{ $category->id }}')"
                                                    class="rounded-md border border-red-300 px-3 py-2 text-red-700 transition hover:bg-red-50"
                                                >Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile: stacked card layout --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($categories as $category)
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                    @if ($category->status === 'active')
                                        <span class="inline-flex shrink-0 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex shrink-0 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">Inactive</span>
                                    @endif
                                </div>

                                <p class="mt-2 line-clamp-2 text-sm text-gray-600">
                                    {{ $category->description ?: '-' }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                                    <a href="{{ route('categories.show', $category) }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50">View</a>
                                    <a href="{{ route('categories.edit', $category) }}" class="rounded-md border border-indigo-300 px-3 py-2 text-sm text-indigo-700 transition hover:bg-indigo-50">Edit</a>
                                    <button
                                        type="button"
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-category-delete-{{ $category->id }}')"
                                        class="rounded-md border border-red-300 px-3 py-2 text-sm text-red-700 transition hover:bg-red-50"
                                    >Delete</button>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500">
                                No categories found.
                            </p>
                        @endforelse
                    </div>

                    {{-- Delete-confirmation modals: rendered once per category (shared by both the table and card
                    triggers above via matching modal names), outside the hidden/md:hidden wrappers so the fixed
                    modal isn't hidden by a display:none ancestor at either breakpoint. --}}
                    @foreach ($categories as $category)
                        <x-modal name="confirm-category-delete-{{ $category->id }}" focusable>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="p-6">
                                @csrf
                                @method('DELETE')

                                <h2 class="font-display text-lg font-semibold text-ink">
                                    {{ __('Delete this category?') }}
                                </h2>

                                <p class="mt-1 font-body text-sm text-slate">
                                    {{ __('This will permanently delete ":name" and cannot be undone.', ['name' => $category->name]) }}
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
                    @endforeach

                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
