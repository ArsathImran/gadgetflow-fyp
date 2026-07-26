<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-xl font-semibold text-ink leading-tight">
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
                            <label for="category_id" class="block font-body text-sm font-medium text-slate-700">{{ __('Category') }}</label>
                            <select
                                id="category_id"
                                name="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                            <label for="name" class="block font-body text-sm font-medium text-slate-700">{{ __('Name') }}</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('name', $isEditing ? $gadget->name : '') }}"
                                required
                                autofocus
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="brand" class="block font-body text-sm font-medium text-slate-700">{{ __('Brand') }}</label>
                                <input
                                    id="brand"
                                    name="brand"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('brand', $isEditing ? $gadget->brand : '') }}"
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('brand')" />
                            </div>

                            <div>
                                <label for="model" class="block font-body text-sm font-medium text-slate-700">{{ __('Model') }}</label>
                                <input
                                    id="model"
                                    name="model"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('model', $isEditing ? $gadget->model : '') }}"
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('model')" />
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block font-body text-sm font-medium text-slate-700">{{ __('Description') }}</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description', $isEditing ? $gadget->description : '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="daily_rental_price" class="block font-body text-sm font-medium text-slate-700">{{ __('Daily Rental Price') }}</label>
                                <input
                                    id="daily_rental_price"
                                    name="daily_rental_price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('daily_rental_price', $isEditing ? $gadget->daily_rental_price : '') }}"
                                    required
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('daily_rental_price')" />
                            </div>

                            <div>
                                <label for="hourly_rental_price" class="block font-body text-sm font-medium text-slate-700">{{ __('Hourly Rental Price') }}</label>
                                <input
                                    id="hourly_rental_price"
                                    name="hourly_rental_price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('hourly_rental_price', $isEditing ? $gadget->hourly_rental_price : '') }}"
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('hourly_rental_price')" />
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="deposit_amount" class="block font-body text-sm font-medium text-slate-700">{{ __('Deposit Amount') }}</label>
                                <input
                                    id="deposit_amount"
                                    name="deposit_amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('deposit_amount', $isEditing ? $gadget->deposit_amount : '') }}"
                                    required
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('deposit_amount')" />
                            </div>

                            <div>
                                <label for="late_fee_per_day" class="block font-body text-sm font-medium text-slate-700">{{ __('Late Fee Per Day') }}</label>
                                <input
                                    id="late_fee_per_day"
                                    name="late_fee_per_day"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('late_fee_per_day', $isEditing ? $gadget->late_fee_per_day : '') }}"
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('late_fee_per_day')" />
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="quantity" class="block font-body text-sm font-medium text-slate-700">{{ __('Quantity') }}</label>
                                <input
                                    id="quantity"
                                    name="quantity"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('quantity', $isEditing ? $gadget->quantity : 1) }}"
                                    required
                                >
                                <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
                            </div>

                            <div>
                                <label for="condition" class="block font-body text-sm font-medium text-slate-700">{{ __('Condition') }}</label>
                                <select
                                    id="condition"
                                    name="condition"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="new" @selected(old('condition', $isEditing ? $gadget->condition : 'good') === 'new')>New</option>
                                    <option value="like_new" @selected(old('condition', $isEditing ? $gadget->condition : 'good') === 'like_new')>Like New</option>
                                    <option value="good" @selected(old('condition', $isEditing ? $gadget->condition : 'good') === 'good')>Good</option>
                                    <option value="fair" @selected(old('condition', $isEditing ? $gadget->condition : 'good') === 'fair')>Fair</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('condition')" />
                            </div>

                            <div>
                                <label for="status" class="block font-body text-sm font-medium text-slate-700">{{ __('Status') }}</label>
                                <select
                                    id="status"
                                    name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="active" @selected(old('status', $isEditing ? $gadget->status : 'active') === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $isEditing ? $gadget->status : '') === 'inactive')>Inactive</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div>
                            <label for="image" class="block font-body text-sm font-medium text-slate-700">{{ __('Image') }}</label>
                            <input
                                id="image"
                                name="image"
                                type="file"
                                class="mt-1 block w-full font-body text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />

                            @if ($isEditing && $gadget->image)
                                <div class="mt-4">
                                    <p class="font-body text-sm font-medium text-slate-500">Current Image</p>
                                    <img
                                        src="{{ asset('storage/' . $gadget->image) }}"
                                        alt="{{ $gadget->name }}"
                                        class="mt-2 h-40 w-40 rounded-lg object-cover border border-gray-200"
                                    >
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="gallery_images" class="block font-body text-sm font-medium text-slate-700">{{ __('Gallery Images') }}</label>
                            <input
                                id="gallery_images"
                                name="gallery_images[]"
                                type="file"
                                multiple
                                class="mt-1 block w-full font-body text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                            >
                            <p class="mt-2 font-body text-sm text-slate-500">
                                Upload up to 6 additional photos for the gadget detail page. These are separate from the cover image above.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('gallery_images')" />
                            <x-input-error class="mt-2" :messages="$errors->get('gallery_images.*')" />

                            @if ($isEditing && !empty($gadget->gallery_images))
                                <div class="mt-4">
                                    <p class="font-body text-sm font-medium text-slate-500">Existing Gallery Images</p>
                                    <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($gadget->gallery_images as $galleryImage)
                                            <label class="overflow-hidden rounded-xl border border-gray-200 bg-cloud">
                                                <img
                                                    src="{{ asset('storage/' . $galleryImage) }}"
                                                    alt="{{ $gadget->name }} gallery image {{ $loop->iteration }}"
                                                    class="h-32 w-full object-cover"
                                                >
                                                <div class="flex items-center gap-2 px-3 py-3 font-body text-sm text-gray-700">
                                                    <input
                                                        type="checkbox"
                                                        name="remove_gallery_images[]"
                                                        value="{{ $galleryImage }}"
                                                        @checked(in_array($galleryImage, old('remove_gallery_images', []), true))
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                    >
                                                    <span>Remove this image</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('remove_gallery_images')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('remove_gallery_images.*')" />
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo px-5 py-2.5 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                {{ __('Save') }}
                            </button>

                            <a href="{{ route('gadgets.index') }}" class="font-body text-sm font-medium text-slate-600 hover:text-slate-900">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
