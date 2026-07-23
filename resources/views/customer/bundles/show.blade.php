<x-app-layout>
    @php
        $galleryImages = collect([$bundle->image])
            ->filter()
            ->merge($bundle->gallery_images ?? [])
            ->values();
        $initialImage = $galleryImages->first();
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $bundle->name }}
                </h2>
                <p class="text-sm text-gray-600">Combo package details and rental request form.</p>
            </div>

            <a href="{{ route('customer.bundles.index', ['type' => $bundle->type]) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                Back to Combos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="grid gap-0 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="bg-gray-100" x-data="{ selectedImage: @js($initialImage ? asset('storage/' . $initialImage) : null) }">
                        @if ($galleryImages->isNotEmpty())
                            <div class="flex min-h-[320px] items-center justify-center overflow-hidden bg-white">
                                <img
                                    :src="selectedImage"
                                    alt="{{ $bundle->name }}"
                                    class="h-full min-h-[320px] w-full object-cover"
                                >
                            </div>

                            <div class="grid grid-cols-4 gap-3 border-t border-gray-200 bg-white p-4 sm:grid-cols-5">
                                @foreach ($galleryImages as $galleryImage)
                                    @php
                                        $galleryImageUrl = asset('storage/' . $galleryImage);
                                    @endphp
                                    <button
                                        type="button"
                                        @click="selectedImage = @js($galleryImageUrl)"
                                        class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 transition hover:border-indigo-300 hover:ring-2 hover:ring-indigo-100"
                                    >
                                        <img
                                            src="{{ $galleryImageUrl }}"
                                            alt="{{ $bundle->name }} image {{ $loop->iteration }}"
                                            class="h-20 w-full object-cover"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="flex min-h-[320px] items-center justify-center text-sm text-gray-400">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-gray-500">
                                    {{ $bundle->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo' }}
                                </p>
                                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $bundle->name }}</h1>
                            </div>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                Walk-in only
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-4">
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Daily Rental Price</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $bundle->daily_rental_price !== null ? number_format($bundle->daily_rental_price, 2) : '-' }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Hourly Rental Price</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $bundle->hourly_rental_price !== null ? number_format($bundle->hourly_rental_price, 2) : '-' }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Deposit</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format((float) ($bundle->deposit_amount ?? 0), 2) }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Late Fee / Day</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format((float) ($bundle->late_fee_per_day ?? 0), 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500">What's Included</h3>
                            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">
                                {{ $bundle->description ?: 'Bundle contents will be listed here soon.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm sm:p-8" x-data="{ rentalType: @js(old('rental_type', 'day')) }">
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-semibold text-gray-900">Request This Combo</h3>
                    <p class="text-sm text-gray-600">Combo rentals are walk-in pickup only. Delivery is not available for packages.</p>
                </div>

                <form method="POST" action="{{ route('customer.rentals.store') }}" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <label for="rental_type" class="block text-sm font-medium text-gray-700">Rental Type</label>
                            <select
                                id="rental_type"
                                name="rental_type"
                                x-model="rentalType"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="day">By Day</option>
                                @if ($bundle->hourly_rental_price !== null)
                                    <option value="hour">By Hour</option>
                                @endif
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rental_type')" />
                        </div>

                        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900">
                            Bundles are walk-in pickup only. Your request will automatically be submitted as a walk-in rental.
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div x-show="rentalType === 'hour'">
                            <label for="rental_hours" class="block text-sm font-medium text-gray-700">Number of Hours</label>
                            <input
                                id="rental_hours"
                                type="number"
                                name="rental_hours"
                                min="1"
                                value="{{ old('rental_hours', 1) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('rental_hours')" />
                        </div>

                        <div x-show="rentalType === 'day'">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input
                                id="start_date"
                                type="date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <div x-show="rentalType === 'day'">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input
                                id="end_date"
                                type="date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <label class="inline-flex items-start gap-3 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="agreement_accepted"
                                value="1"
                                class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                @checked(old('agreement_accepted'))
                                required
                            >
                            <span>I understand this combo is for walk-in pickup only and will follow the rental terms shown by GadgetFlow.</span>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('agreement_accepted')" />
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                            Submit Combo Request
                        </button>
                        <a href="{{ route('customer.bundles.index', ['type' => $bundle->type]) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
