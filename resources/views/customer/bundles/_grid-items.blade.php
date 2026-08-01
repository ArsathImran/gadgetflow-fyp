@foreach ($bundles as $bundle)
    <a
        href="{{ route('customer.bundles.show', $bundle) }}"
        class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-lg"
    >
        <div class="flex h-44 items-center justify-center overflow-hidden bg-cloud">
            @if ($bundle->image)
                <img
                    src="{{ asset('storage/' . $bundle->image) }}"
                    alt="{{ $bundle->name }}"
                    class="max-h-40 max-w-full object-contain transition duration-300 group-hover:scale-105"
                >
            @else
                <span class="font-body text-sm text-gray-400">No bundle image</span>
            @endif
        </div>

        <div class="p-4">
            <div class="flex items-start justify-between gap-2">
                <h3 class="truncate font-display text-sm font-semibold text-ink">
                    {{ $bundle->name }}
                </h3>
                <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 font-body text-[11px] font-semibold text-amber-800">
                    Walk-in only
                </span>
            </div>

            <p class="mt-1 truncate font-body text-xs text-slate">
                {{ $bundle->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo' }}
            </p>

            <p class="mt-2 line-clamp-1 font-body text-xs text-slate">
                {{ $bundle->description ?: 'Curated combo package details available on the product page.' }}
            </p>

            @if ($bundle->daily_rental_price !== null)
                <div class="mt-2">
                    <x-spec-chip>
                        RM{{ number_format($bundle->daily_rental_price, 0) }}/day
                    </x-spec-chip>
                </div>
            @endif

            <div class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-indigo px-3 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                View Combo
            </div>
        </div>
    </a>
@endforeach
