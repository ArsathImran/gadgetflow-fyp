<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Request Rental') }}
                </h2>
                <p class="text-sm text-gray-600">Choose how you want to rent {{ $gadget->name }}.</p>
            </div>

            <a href="{{ route('customer.gadgets.show', $gadget) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                {{ __('Back to Gadget') }}
            </a>
        </div>
    </x-slot>

    @php
        $selectedRentalType = old('rental_type', 'day');
        $selectedPickupType = old('pickup_type', 'walk_in');
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="grid gap-0 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                    <div class="bg-gray-100 p-6 sm:p-8">
                        @if ($gadget->image)
                            <div class="aspect-square w-full max-w-sm overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                                <img
                                    src="{{ asset('storage/' . $gadget->image) }}"
                                    alt="{{ $gadget->name }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>
                        @else
                            <div class="flex aspect-square w-full max-w-sm items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-white text-sm text-gray-400">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        @if ($errors->any())
                            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                Please fix the highlighted fields and try again.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('customer.rentals.store') }}" enctype="multipart/form-data" class="space-y-6" id="rental-form">
                            @csrf
                            <input type="hidden" name="gadget_id" value="{{ $gadget->id }}">

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">Gadget</p>
                                    <p class="mt-1 text-base font-semibold text-gray-900">{{ $gadget->name }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $gadget->category?->name ?? '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">Pricing</p>
                                    <p class="mt-1 text-sm text-gray-900">Daily: {{ number_format($gadget->daily_rental_price, 2) }}</p>
                                    <p class="text-sm text-gray-900">Hourly: {{ number_format($gadget->hourly_rental_price ?? $gadget->daily_rental_price, 2) }}</p>
                                    <p class="text-sm text-gray-900">Deposit: {{ number_format($gadget->deposit_amount, 2) }}</p>
                                    <p class="mt-2 text-sm text-indigo-700">Your points balance: <span class="font-semibold">{{ number_format(auth()->user()->loyalty_points) }}</span> pts</p>
                                </div>
                            </div>

                            @if (auth()->user()->loyalty_points > 0)
                                <div
                                    class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4"
                                    x-data="{ redeemPoints: {{ (int) old('redeem_points', 0) }}, balance: {{ (int) auth()->user()->loyalty_points }}, rate: {{ (float) config('loyalty.redemption_rate') }} }"
                                >
                                    <div class="flex items-center justify-between">
                                        <label for="redeem_points" class="text-sm font-medium text-gray-700">Redeem Points</label>
                                        <span class="text-xs text-gray-500">Balance: <span class="font-semibold text-indigo-700">{{ number_format(auth()->user()->loyalty_points) }}</span> pts</span>
                                    </div>
                                    <input
                                        id="redeem_points"
                                        name="redeem_points"
                                        type="number"
                                        min="0"
                                        :max="balance"
                                        x-model.number="redeemPoints"
                                        @input="if (redeemPoints > balance) redeemPoints = balance; if (redeemPoints < 0 || isNaN(redeemPoints)) redeemPoints = 0;"
                                        class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    <p class="mt-2 text-sm text-indigo-700" x-show="redeemPoints > 0">
                                        Redeem <span x-text="redeemPoints"></span> points for RM<span x-text="(redeemPoints * rate).toFixed(2)"></span> off.
                                    </p>
                                    @error('redeem_points')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div>
                                <p class="text-sm font-medium text-gray-700">Rental Type</p>
                                <div class="mt-2 flex flex-wrap gap-3">
                                    <label class="inline-flex items-center gap-2 rounded-xl border px-4 py-3 text-sm {{ $selectedRentalType === 'hour' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700' }}">
                                        <input type="radio" name="rental_type" value="hour" @checked($selectedRentalType === 'hour')>
                                        Hour
                                    </label>
                                    <label class="inline-flex items-center gap-2 rounded-xl border px-4 py-3 text-sm {{ $selectedRentalType === 'day' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700' }}">
                                        <input type="radio" name="rental_type" value="day" @checked($selectedRentalType === 'day')>
                                        Day
                                    </label>
                                </div>
                                @error('rental_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="hour-fields" class="rounded-2xl border border-gray-200 p-4">
                                <label for="rental_hours" class="block text-sm font-medium text-gray-700">Number of Hours</label>
                                <input
                                    id="rental_hours"
                                    name="rental_hours"
                                    type="number"
                                    min="1"
                                    value="{{ old('rental_hours') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                @error('rental_hours')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="day-fields" class="grid gap-4 sm:grid-cols-2 rounded-2xl border border-gray-200 p-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input
                                        id="start_date"
                                        name="start_date"
                                        type="date"
                                        value="{{ old('start_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input
                                        id="end_date"
                                        name="end_date"
                                        type="date"
                                        value="{{ old('end_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-700">Pickup Type</p>
                                <div class="mt-2 flex flex-wrap gap-3">
                                    <label class="inline-flex items-center gap-2 rounded-xl border px-4 py-3 text-sm {{ $selectedPickupType === 'walk_in' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700' }}">
                                        <input type="radio" name="pickup_type" value="walk_in" @checked($selectedPickupType === 'walk_in')>
                                        Walk-in
                                    </label>
                                    <label class="inline-flex items-center gap-2 rounded-xl border px-4 py-3 text-sm {{ $selectedPickupType === 'delivery' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700' }}">
                                        <input type="radio" name="pickup_type" value="delivery" @checked($selectedPickupType === 'delivery')>
                                        Delivery
                                    </label>
                                </div>
                                @error('pickup_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="delivery-fields" class="grid gap-4 rounded-2xl border border-gray-200 p-4">
                                <div>
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700">Delivery Address</label>
                                    <textarea
                                        id="delivery_address"
                                        name="delivery_address"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >{{ old('delivery_address') }}</textarea>
                                    @error('delivery_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-indigo-700">Return Instructions</p>
                                    <p class="mt-2 text-sm text-indigo-900">
                                        Since this item was received by delivery, please return it yourself via courier or postage, at your own cost, once your rental period ends.
                                    </p>
                                    <div class="mt-3 rounded-lg bg-white/80 p-3">
                                        <p class="text-xs uppercase tracking-wide text-indigo-700">Return Address</p>
                                        <p class="mt-1 whitespace-pre-line text-sm font-semibold text-indigo-950">{{ config('company.return_address') }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input
                                            id="phone_number"
                                            name="phone_number"
                                            type="text"
                                            value="{{ old('phone_number') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        @error('phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        @if (auth()->user()->id_document_path)
                                            <label class="block text-sm font-medium text-gray-700">Supporting ID Document</label>
                                            <p class="mt-1 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                                                Using your verified ID document on file (uploaded on {{ auth()->user()->id_document_uploaded_at?->format('M j, Y') }}).
                                                <a href="{{ route('profile.edit') }}" class="font-semibold underline hover:text-emerald-800">Update it on your profile</a>
                                            </p>
                                        @else
                                            <label for="id_document" class="block text-sm font-medium text-gray-700">Supporting ID Document</label>
                                            <input
                                                id="id_document"
                                                name="id_document"
                                                type="file"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            <p class="mt-1 text-xs text-gray-500">This will be saved to your profile for future rentals.</p>
                                            @error('id_document')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-4 text-sm text-gray-700">
                                <p class="font-semibold text-gray-900">Agreement</p>
                                <p class="mt-2">
                                    If the product is missing or damaged, you must pay the required amount. Your ID document may be used for police report purposes if illegal activities occur.
                                </p>
                                <button type="button" id="open-agreement" class="mt-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                    View Agreement
                                </button>
                            </div>

                            <div class="flex items-start gap-3">
                                <input id="agreement_accepted" name="agreement_accepted" type="checkbox" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('agreement_accepted'))>
                                <label for="agreement_accepted" class="text-sm text-gray-700">
                                    I have read and agree to the rental agreement.
                                </label>
                            </div>
                            @error('agreement_accepted')
                                <p class="-mt-4 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="flex items-center gap-3">
                                <button type="submit" id="submit-button" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:bg-indigo-300">
                                    {{ __('Submit Request') }}
                                </button>

                                <a href="{{ route('customer.gadgets.show', $gadget) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="agreement-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Rental Agreement</h3>
                    <p class="mt-1 text-sm text-gray-600">Please read this before continuing.</p>
                </div>
                <button type="button" id="close-agreement" class="rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">
                    <span class="text-lg leading-none">&times;</span>
                </button>
            </div>

            <div class="mt-5 rounded-2xl bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                If the product is missing or damaged, you must pay the required amount. Your ID document may be used for police report purposes if illegal activities occur.
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" id="acknowledge-agreement" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                    I Understand
                </button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const rentalTypeRadios = document.querySelectorAll('input[name="rental_type"]');
            const pickupTypeRadios = document.querySelectorAll('input[name="pickup_type"]');
            const hourFields = document.getElementById('hour-fields');
            const dayFields = document.getElementById('day-fields');
            const rentalHours = document.getElementById('rental_hours');
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const deliveryFields = document.getElementById('delivery-fields');
            const deliveryAddress = document.getElementById('delivery_address');
            const phoneNumber = document.getElementById('phone_number');
            const idDocument = document.getElementById('id_document');
            const agreementCheckbox = document.getElementById('agreement_accepted');
            const submitButton = document.getElementById('submit-button');
            const modal = document.getElementById('agreement-modal');
            const openAgreement = document.getElementById('open-agreement');
            const closeAgreement = document.getElementById('close-agreement');
            const acknowledgeAgreement = document.getElementById('acknowledge-agreement');

            const rentalTypeValue = () => document.querySelector('input[name="rental_type"]:checked')?.value || 'day';
            const pickupTypeValue = () => document.querySelector('input[name="pickup_type"]:checked')?.value || 'walk_in';

            const updateOptionLabels = (radios) => {
                radios.forEach((radio) => {
                    const label = radio.closest('label');
                    label.classList.toggle('border-indigo-600', radio.checked);
                    label.classList.toggle('bg-indigo-50', radio.checked);
                    label.classList.toggle('text-indigo-700', radio.checked);
                    label.classList.toggle('border-gray-300', ! radio.checked);
                    label.classList.toggle('bg-white', ! radio.checked);
                    label.classList.toggle('text-gray-700', ! radio.checked);
                });
            };

            const toggleFields = () => {
                const isHour = rentalTypeValue() === 'hour';
                const isDelivery = pickupTypeValue() === 'delivery';

                hourFields.classList.toggle('hidden', ! isHour);
                dayFields.classList.toggle('hidden', isHour);
                deliveryFields.classList.toggle('hidden', ! isDelivery);

                rentalHours.required = isHour;
                startDate.required = ! isHour;
                endDate.required = ! isHour;
                deliveryAddress.required = isDelivery;
                phoneNumber.required = isDelivery;

                if (idDocument) {
                    idDocument.required = isDelivery;
                }

                updateOptionLabels(pickupTypeRadios);
            };

            const toggleSubmit = () => {
                submitButton.disabled = ! agreementCheckbox.checked;
            };

            rentalTypeRadios.forEach((radio) => radio.addEventListener('change', toggleFields));
            pickupTypeRadios.forEach((radio) => radio.addEventListener('change', toggleFields));
            agreementCheckbox.addEventListener('change', toggleSubmit);

            openAgreement.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            const hideModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            closeAgreement.addEventListener('click', hideModal);
            acknowledgeAgreement.addEventListener('click', () => {
                agreementCheckbox.checked = true;
                toggleSubmit();
                hideModal();
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    hideModal();
                }
            });

            toggleFields();
            toggleSubmit();
        })();
    </script>
</x-app-layout>
