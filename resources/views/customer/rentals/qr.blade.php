<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rental QR - #{{ $rental->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <main class="flex min-h-screen items-center justify-center px-6 py-10">
        <div class="w-full max-w-2xl rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-2xl backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-sky-300">Pickup / Return Verification</p>

            <h1 class="mt-4 text-3xl font-semibold text-white sm:text-4xl">
                {{ $rental->gadget?->name ?? 'Rental Gadget' }}
            </h1>

            <p class="mt-3 text-base text-slate-300">
                Rental ID #{{ $rental->id }}
            </p>

            <div class="mx-auto mt-8 flex w-full max-w-sm justify-center rounded-3xl bg-white p-6 shadow-lg">
                {!! $qrSvg !!}
            </div>

            <p class="mt-6 text-sm text-slate-300">
                Hold this QR code up for the admin to scan during pickup or return verification.
            </p>

            <div class="mt-8 flex justify-center">
                <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center rounded-md border border-white/15 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                    Back to My Rentals
                </a>
            </div>
        </div>
    </main>
</body>
</html>
