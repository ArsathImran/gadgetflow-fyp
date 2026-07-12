<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $type === 'wedding' ? __('Wedding Combo') : __('Short Film Combo') }}
                </h2>
                <p class="text-sm text-gray-600">Explore curated gadget packages grouped for specific production needs.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($bundles->count())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($bundles as $bundle)
                        <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                            @if ($bundle->image)
                                <img
                                    src="{{ asset('storage/' . $bundle->image) }}"
                                    alt="{{ $bundle->name }}"
                                    class="h-52 w-full object-cover"
                                >
                            @else
                                <div class="flex h-52 items-center justify-center bg-gray-100 text-sm text-gray-400">
                                    No bundle image
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $bundle->name }}</h3>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $bundle->type === 'wedding' ? 'bg-pink-100 text-pink-800' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $bundle->type === 'wedding' ? 'Wedding' : 'Short Film' }}
                                    </span>
                                </div>

                                @if ($bundle->description)
                                    <p class="mt-3 text-sm leading-6 text-gray-600">{{ $bundle->description }}</p>
                                @endif

                                <dl class="mt-5 grid gap-3 rounded-2xl bg-gray-50 p-4 text-sm text-gray-700">
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-gray-500">Daily Rental Price</dt>
                                        <dd class="font-semibold text-gray-900">{{ $bundle->daily_rental_price !== null ? number_format($bundle->daily_rental_price, 2) : '-' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-gray-500">Hourly Rental Price</dt>
                                        <dd class="font-semibold text-gray-900">{{ $bundle->hourly_rental_price !== null ? number_format($bundle->hourly_rental_price, 2) : '-' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-gray-500">Deposit Amount</dt>
                                        <dd class="font-semibold text-gray-900">{{ number_format((float) ($bundle->deposit_amount ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-gray-500">Late Fee Per Day</dt>
                                        <dd class="font-semibold text-gray-900">{{ number_format((float) ($bundle->late_fee_per_day ?? 0), 2) }}</dd>
                                    </div>
                                </dl>

                                <div class="mt-5">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">What's Included</p>
                                    <div class="mt-3 rounded-xl border border-gray-100 px-4 py-3 text-sm leading-6 text-gray-700">
                                        {{ $bundle->description ?: 'Bundle contents will be listed here soon.' }}
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center justify-between gap-3">
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                        Walk-in pickup only
                                    </span>
                                    <a href="{{ route('customer.bundles.show', $bundle) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                        View Combo
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $bundles->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm">
                    <p class="text-base font-semibold text-gray-900">No bundles available right now.</p>
                    <p class="mt-2 text-sm text-gray-500">Please check back later for curated package rentals.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
