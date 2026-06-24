<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $gadget->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Gadget details</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('gadgets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    {{ __('Back') }}
                </a>

                <a href="{{ route('gadgets.edit', $gadget) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                        <div>
                            @if ($gadget->image)
                                <img
                                    src="{{ asset('storage/' . $gadget->image) }}"
                                    alt="{{ $gadget->name }}"
                                    class="h-56 w-full rounded-xl object-cover border border-gray-200"
                                >
                            @else
                                <div class="flex h-56 items-center justify-center rounded-xl border border-dashed border-gray-300 text-sm text-gray-400">
                                    No image available
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Name</p>
                                <p class="mt-1 text-base text-gray-900">{{ $gadget->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">Category</p>
                                <p class="mt-1 text-base text-gray-900">{{ $gadget->category?->name ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <div class="mt-1">
                                    @if ($gadget->status === 'active')
                                        <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Description</p>
                        <p class="mt-1 text-base text-gray-900 whitespace-pre-line">{{ $gadget->description ?: '-' }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Daily Rental Price</p>
                            <p class="mt-1 text-base text-gray-900">{{ number_format($gadget->daily_rental_price, 2) }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Deposit Amount</p>
                            <p class="mt-1 text-base text-gray-900">{{ number_format($gadget->deposit_amount, 2) }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Quantity</p>
                            <p class="mt-1 text-base text-gray-900">{{ $gadget->quantity }}</p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Created At</p>
                            <p class="mt-1 text-base text-gray-900">{{ $gadget->created_at }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Updated At</p>
                            <p class="mt-1 text-base text-gray-900">{{ $gadget->updated_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
