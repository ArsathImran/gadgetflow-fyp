<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.169.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                        {{ isset($category) ? __('Edit Category') : __('Create Category') }}
                    </h2>
                    <p class="mt-1 font-body text-sm text-slate">{{ __('Organize gadgets into browsable categories.') }}</p>
                </div>
            </div>

            <a href="{{ route('categories.index') }}" class="font-body text-sm text-slate-600 hover:text-slate-900">
                {{ __('← Back to Categories') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="h-1 bg-gradient-to-r from-indigo to-cyan"></div>

                <div class="p-6 text-gray-900">
                    @php
                        $isEditing = isset($category);
                    @endphp

                    <form method="POST" action="{{ $isEditing ? route('categories.update', $category) : route('categories.store') }}" class="space-y-6">
                        @csrf

                        @if ($isEditing)
                            @method('PUT')
                        @endif

                        <div>
                            <label for="name" class="block font-body text-sm font-medium text-slate-700">{{ __('Name') }}</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('name', $isEditing ? $category->name : '') }}"
                                required
                                autofocus
                            >
                            <p class="mt-1 font-body text-xs text-slate-500">{{ __('Shown to customers when browsing gadgets.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div x-data="{ count: {{ strlen(old('description', $isEditing ? $category->description : '') ?? '') }} }">
                            <label for="description" class="block font-body text-sm font-medium text-slate-700">{{ __('Description') }}</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                x-on:input="count = $event.target.value.length"
                                class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description', $isEditing ? $category->description : '') }}</textarea>
                            <p class="mt-1 font-mono text-xs text-slate-400"><span x-text="count"></span> {{ __('characters') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <span class="block font-body text-sm font-medium text-slate-700">{{ __('Status') }}</span>
                            <div class="mt-2 inline-flex gap-2">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="active"
                                        class="peer sr-only"
                                        @checked(old('status', $isEditing ? $category->status : 'active') === 'active')
                                    >
                                    <span class="rounded-full bg-slate-100 px-4 py-2 font-body text-sm font-medium text-slate-600 transition peer-checked:bg-indigo peer-checked:text-white">
                                        {{ __('Active') }}
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="inactive"
                                        class="peer sr-only"
                                        @checked(old('status', $isEditing ? $category->status : '') === 'inactive')
                                    >
                                    <span class="rounded-full bg-slate-100 px-4 py-2 font-body text-sm font-medium text-slate-600 transition peer-checked:bg-indigo peer-checked:text-white">
                                        {{ __('Inactive') }}
                                    </span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo px-5 py-2.5 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                {{ __('Save') }}
                            </button>

                            <a href="{{ route('categories.index') }}" class="font-body text-sm font-medium text-slate-600 hover:text-slate-900">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
