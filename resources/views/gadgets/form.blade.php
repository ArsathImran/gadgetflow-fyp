<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($gadget) ? __('Edit Gadget') : __('Create Gadget') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $isEditing = isset($gadget);
                    @endphp

                    <form
                        method="POST"
                        action="{{ $isEditing ? route('gadgets.update', $gadget) : route('gadgets.store') }}"
                        class="space-y-6"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        @if ($isEditing)
                            @method('PUT')
                        @endif

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select
                                id="category_id"
                                name="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id', $isEditing ? $gadget->category_id : '') === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $isEditing ? $gadget->name : '')"
                                required
                                autofocus
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description', $isEditing ? $gadget->description : '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="daily_rental_price" :value="__('Daily Rental Price')" />
                                <x-text-input
                                    id="daily_rental_price"
                                    name="daily_rental_price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full"
                                    :value="old('daily_rental_price', $isEditing ? $gadget->daily_rental_price : '')"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('daily_rental_price')" />
                            </div>

                            <div>
                                <x-input-label for="deposit_amount" :value="__('Deposit Amount')" />
                                <x-text-input
                                    id="deposit_amount"
                                    name="deposit_amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full"
                                    :value="old('deposit_amount', $isEditing ? $gadget->deposit_amount : '')"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('deposit_amount')" />
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="quantity" :value="__('Quantity')" />
                                <x-text-input
                                    id="quantity"
                                    name="quantity"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full"
                                    :value="old('quantity', $isEditing ? $gadget->quantity : 1)"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select
                                    id="status"
                                    name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="active" @selected(old('status', $isEditing ? $gadget->status : 'active') === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $isEditing ? $gadget->status : '') === 'inactive')>Inactive</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Image')" />
                            <input
                                id="image"
                                name="image"
                                type="file"
                                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />

                            @if ($isEditing && $gadget->image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-500">Current Image</p>
                                    <img
                                        src="{{ asset('storage/' . $gadget->image) }}"
                                        alt="{{ $gadget->name }}"
                                        class="mt-2 h-40 w-40 rounded-lg object-cover border border-gray-200"
                                    >
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>

                            <a href="{{ route('gadgets.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
