@foreach ($gadgets as $gadget)
    @php
        $isOutOfStock = $gadget->quantity <= 0;
        $averageRating = $gadget->averageRating();
        $reviewsCount = $gadget->reviewsCount();
    @endphp
    <{{ $isOutOfStock ? 'div' : 'a' }}
        @if (! $isOutOfStock) href="{{ route('customer.gadgets.show', $gadget) }}" @endif
        class="group bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden {{ $isOutOfStock ? 'opacity-70' : 'hover:shadow-lg transition' }}"
    >
        <div class="h-44 bg-cloud flex items-center justify-center overflow-hidden relative">
            @if($gadget->image)
                <img src="{{ asset('storage/' . $gadget->image) }}"
                     alt="{{ $gadget->name }}"
                     class="max-h-40 max-w-full object-contain {{ $isOutOfStock ? 'grayscale' : 'group-hover:scale-105 transition duration-300' }}">
            @else
                <span class="text-gray-400 text-sm font-body">No Image</span>
            @endif

            @if ($isOutOfStock)
                <span class="absolute top-2 right-2 rounded-full bg-ink/90 px-2.5 py-1 font-body text-[11px] font-semibold uppercase tracking-wide text-white">
                    Out of Stock
                </span>
            @endif
        </div>

        <div class="p-4">
            <h3 class="font-display text-sm font-semibold text-ink truncate">
                {{ $gadget->name }}
            </h3>

            @if ($gadget->brand || $gadget->model)
                <p class="mt-1 font-body text-xs text-slate">
                    {{ collect([$gadget->brand, $gadget->model])->filter()->implode(' ') }}
                </p>
            @endif

            <p class="mt-1 font-body text-xs text-slate">
                {{ $gadget->category->name ?? 'Uncategorized' }}
            </p>

            <div class="mt-2">
                <x-spec-chip>
                    RM{{ number_format($gadget->daily_rental_price, 0) }}/day &middot; {{ $isOutOfStock ? 'Out of stock' : $gadget->quantity . ' in stock' }}
                </x-spec-chip>
            </div>

            <div class="mt-3 flex items-center gap-2 text-xs">
                @if ($averageRating)
                    <span class="text-amber-500">
                        @for ($star = 1; $star <= 5; $star++)
                            <span class="{{ $star <= round($averageRating) ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                        @endfor
                    </span>
                    <span class="font-mono font-medium text-gray-700">{{ number_format($averageRating, 1) }}</span>
                    <span class="font-mono text-gray-500">({{ $reviewsCount }})</span>
                @else
                    <span class="font-body text-gray-400">No reviews yet</span>
                @endif
            </div>

            @if ($isOutOfStock)
                <div class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-gray-200 px-3 py-2 text-sm font-body font-semibold text-gray-500">
                    Currently Unavailable
                </div>
            @else
                <div class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-indigo px-3 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                    View Product
                </div>
            @endif
        </div>
    </{{ $isOutOfStock ? 'div' : 'a' }}>
@endforeach
