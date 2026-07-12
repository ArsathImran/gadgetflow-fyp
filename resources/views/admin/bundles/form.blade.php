<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($bundle) ? __('Edit Bundle') : __('Create Bundle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $isEditing = isset($bundle);
                    @endphp

                    <form
                        method="POST"
                        action="{{ $isEditing ? route('admin.bundles.update', $bundle) : route('admin.bundles.store') }}"
                        class="space-y-6"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        @if ($isEditing)
                            @method('PUT')
                        @endif

                        <div>
                            <x-input-label for="name" :value="__('Bundle Name')" />
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $isEditing ? $bundle->name : '')"
                                required
                                autofocus
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="type" :value="__('Bundle Type')" />
                                <select
                                    id="type"
                                    name="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="wedding" @selected(old('type', $isEditing ? $bundle->type : 'wedding') === 'wedding')>Wedding</option>
                                    <option value="short_film" @selected(old('type', $isEditing ? $bundle->type : '') === 'short_film')>Short Film</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select
                                    id="status"
                                    name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="active" @selected(old('status', $isEditing ? $bundle->status : 'active') === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $isEditing ? $bundle->status : '') === 'inactive')>Inactive</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('What’s Included')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="What's included (e.g. DSLR camera, 2 softbox lights, tripod, wireless mic)"
                            >{{ old('description', $isEditing ? $bundle->description : '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="space-y-4 rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Pricing</h3>
                                <p class="mt-1 text-sm text-indigo-900">Bundles are walk-in pickup only — delivery is not available for combo packages.</p>
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
                                        :value="old('daily_rental_price', $isEditing ? $bundle->daily_rental_price : '')"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('daily_rental_price')" />
                                </div>

                                <div>
                                    <x-input-label for="hourly_rental_price" :value="__('Hourly Rental Price')" />
                                    <x-text-input
                                        id="hourly_rental_price"
                                        name="hourly_rental_price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 block w-full"
                                        :value="old('hourly_rental_price', $isEditing ? $bundle->hourly_rental_price : '')"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('hourly_rental_price')" />
                                </div>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="deposit_amount" :value="__('Deposit Amount')" />
                                    <x-text-input
                                        id="deposit_amount"
                                        name="deposit_amount"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 block w-full"
                                        :value="old('deposit_amount', $isEditing ? $bundle->deposit_amount : 0)"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('deposit_amount')" />
                                </div>

                                <div>
                                    <x-input-label for="late_fee_per_day" :value="__('Late Fee Per Day')" />
                                    <x-text-input
                                        id="late_fee_per_day"
                                        name="late_fee_per_day"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 block w-full"
                                        :value="old('late_fee_per_day', $isEditing ? $bundle->late_fee_per_day : 0)"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('late_fee_per_day')" />
                                </div>
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

                            @if ($isEditing && $bundle->image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-500">Current Image</p>
                                    <img
                                        src="{{ asset('storage/' . $bundle->image) }}"
                                        alt="{{ $bundle->name }}"
                                        class="mt-2 h-40 w-40 rounded-lg border border-gray-200 object-cover"
                                    >
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>

                            <a href="{{ route('admin.bundles.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
