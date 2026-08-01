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
                    @if ($bundles->count())
                        <div
                            x-data="infiniteGrid({
                                nextPageUrl: @js($bundles->nextPageUrl()),
                                loadedCount: {{ $bundles->count() }},
                                total: {{ $bundles->total() }},
                            })"
                            class="mt-2"
                        >
                            <p class="mb-4 font-body text-sm text-slate" x-text="`Showing ${loadedCount} of ${total} results`"></p>

                            <div x-ref="grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                @include('customer.bundles._grid-items')
                            </div>

                            <div x-ref="sentinel"></div>

                            <div x-show="loading" x-cloak class="mt-8 flex justify-center">
                                <span class="h-6 w-6 animate-spin rounded-full border-2 border-indigo border-t-transparent"></span>
                            </div>

                            <div x-show="!loading && !nextPageUrl" x-cloak class="mt-8 text-center font-body text-xs text-slate-400">
                                You&rsquo;ve reached the end of the list.
                            </div>
                        </div>
                    @else
                        <div class="mt-2 rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="font-display text-base font-semibold text-ink">No bundles available right now.</p>
                            <p class="mt-2 font-body text-sm text-gray-500">Please check back later for curated package rentals.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
