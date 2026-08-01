<x-app-layout>
    @php
        $galleryImages = collect([$gadget->image])
            ->filter()
            ->merge($gadget->gallery_images ?? [])
            ->values();
        $initialImage = $galleryImages->first();
        $averageRating = $gadget->averageRating();
        $reviewsCount = $gadget->reviewsCount();
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $gadget->name }}
                </h2>
                <p class="text-sm text-gray-600">Full gadget details</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    {{ __('Back to Browse') }}
                </a>
                <a href="{{ route('rentals.create', $gadget->id) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                    {{ __('Request Rental') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                    <div class="bg-gray-100 p-6 sm:p-8" x-data="{ selectedImage: @js($initialImage ? asset('storage/' . $initialImage) : null) }">
                        @if ($galleryImages->isNotEmpty())
                            <div class="aspect-square w-full max-w-sm overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                                <img
                                    :src="selectedImage"
                                    alt="{{ $gadget->name }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>

                            <div class="mt-4 grid max-w-sm grid-cols-4 gap-3 sm:grid-cols-5">
                                @foreach ($galleryImages as $galleryImage)
                                    @php
                                        $galleryImageUrl = asset('storage/' . $galleryImage);
                                    @endphp
                                    <button
                                        type="button"
                                        @click="selectedImage = @js($galleryImageUrl)"
                                        :class="selectedImage === @js($galleryImageUrl) ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-indigo-300 hover:ring-2 hover:ring-indigo-100'"
                                        class="aspect-square overflow-hidden rounded-xl border bg-white transition"
                                    >
                                        <img
                                            src="{{ $galleryImageUrl }}"
                                            alt="{{ $gadget->name }} image {{ $loop->iteration }}"
                                            class="h-full w-full object-cover"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="flex aspect-square w-full max-w-sm items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-white text-sm text-gray-400">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-gray-500">{{ $gadget->category?->name ?? 'Uncategorized' }}</p>
                                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $gadget->name }}</h1>
                            </div>
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                In Stock
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-4">
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Daily Rental Price</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($gadget->daily_rental_price, 2) }}</p>
                                @if (auth()->check() && auth()->user()->isCustomer() && auth()->user()->loyalty_points > 0)
                                    <p class="mt-1 text-xs text-indigo-700">{{ number_format(auth()->user()->loyalty_points) }} points available</p>
                                @endif
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Deposit</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($gadget->deposit_amount, 2) }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Quantity</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $gadget->quantity }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">Customer Rating</p>
                                @if ($averageRating)
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-lg leading-none text-amber-500">
                                            @for ($star = 1; $star <= 5; $star++)
                                                <span class="{{ $star <= round($averageRating) ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                            @endfor
                                        </span>
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">{{ $reviewsCount }} review{{ $reviewsCount === 1 ? '' : 's' }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-500">No reviews yet</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                <p class="text-sm text-gray-500">Brand</p>
                                <p class="mt-1 text-base font-semibold text-gray-900">{{ $gadget->brand ?: '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                <p class="text-sm text-gray-500">Model</p>
                                <p class="mt-1 text-base font-semibold text-gray-900">{{ $gadget->model ?: '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                <p class="text-sm text-gray-500">Condition</p>
                                <p class="mt-1 text-base font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $gadget->condition)) }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500">Description</h3>
                            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">
                                {{ $gadget->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-3xl bg-white shadow-sm">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Recent Reviews</h3>
                            <p class="mt-1 text-sm text-gray-500">Feedback from customers who completed a rental for this gadget.</p>
                        </div>
                        @if ($averageRating)
                            <div class="text-right">
                                <div class="text-sm font-semibold text-amber-600">{{ number_format($averageRating, 1) }} / 5</div>
                                <div class="text-xs text-gray-500">{{ $reviewsCount }} total review{{ $reviewsCount === 1 ? '' : 's' }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 space-y-4">
                        @forelse ($gadget->reviews as $review)
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $review->user?->name ?? 'Customer' }}</p>
                                        <div class="mt-1 text-amber-500">
                                            @for ($star = 1; $star <= 5; $star++)
                                                <span class="{{ $star <= $review->rating ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                            @endfor
                                            <span class="ml-2 text-xs font-medium text-gray-500">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $review->created_at->format('Y-m-d') }}</p>
                                </div>

                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">
                                    {{ $review->comment ?: 'No written comment provided.' }}
                                </p>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-10 text-center">
                                <p class="text-sm font-semibold text-gray-900">No reviews yet.</p>
                                <p class="mt-2 text-sm text-gray-500">Completed customer rentals will start appearing here once reviews are submitted.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
