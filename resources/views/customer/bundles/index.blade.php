<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                {{ $type === 'wedding' ? __('Wedding Combo') : __('Short Film Combo') }}
            </h2>
            <p class="font-body text-sm text-slate">
                Explore curated combo packages for event shoots and production days.
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900">
                    <div class="mt-2">
                        @if ($bundles->count())
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
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
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                                <p class="font-display text-base font-semibold text-ink">No bundles available right now.</p>
                                <p class="mt-2 font-body text-sm text-gray-500">Please check back later for curated package rentals.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        {{ $bundles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
