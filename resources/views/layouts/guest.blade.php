@props([
    'title' => 'Welcome back',
    'subtitle' => 'Access your GadgetFlow account and keep your rentals moving.',
    'eyebrow' => 'Premium Gadget Access',
    'panelTitle' => 'GadgetFlow',
    'panelText' => 'Rent premium gadgets anytime, anywhere.',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased" style="font-family: 'Manrope', sans-serif;">
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(96,165,250,0.22),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(168,85,247,0.20),_transparent_30%),linear-gradient(135deg,_#020617_0%,_#0f172a_48%,_#111827_100%)]">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-500/30 blur-3xl animate-pulse"></div>
                <div class="absolute right-0 top-1/3 h-80 w-80 rounded-full bg-violet-500/20 blur-3xl animate-pulse"></div>
                <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl animate-pulse"></div>
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:72px_72px] opacity-20"></div>
            </div>

            <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-4 py-6 sm:px-6 lg:px-8">
                <div class="grid w-full overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 shadow-[0_30px_120px_rgba(15,23,42,0.55)] backdrop-blur-xl lg:grid-cols-[minmax(0,540px)_minmax(0,1fr)]">
                    <section class="relative border-b border-white/10 bg-slate-950/70 p-6 sm:p-8 lg:border-b-0 lg:border-r lg:p-12">
                        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.04),transparent_22%,transparent_100%)]"></div>
                        <div class="relative mx-auto flex min-h-full w-full max-w-md flex-col justify-center">
                            <a href="/" class="inline-flex items-center gap-3 text-white transition duration-300 hover:opacity-90">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/10 shadow-lg shadow-blue-950/30">
                                    <x-application-logo class="h-7 w-7 fill-current text-white" />
                                </span>
                                <span>
                                    <span class="block text-lg font-semibold tracking-[0.18em] text-blue-200/80">{{ $panelTitle }}</span>
                                    <span class="block text-xs uppercase tracking-[0.32em] text-slate-400">Premium SaaS Access</span>
                                </span>
                            </a>

                            <div class="mt-10">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-blue-200/70">{{ $eyebrow }}</p>
                                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">{{ $title }}</h1>
                                <p class="mt-4 max-w-md text-sm leading-6 text-slate-300 sm:text-base">{{ $subtitle }}</p>
                            </div>

                            <div class="relative mt-8 rounded-[1.75rem] border border-white/10 bg-white/10 p-5 shadow-2xl shadow-slate-950/30 backdrop-blur-xl sm:p-6">
                                {{ $slot }}
                            </div>
                        </div>
                    </section>

                    <aside class="relative hidden overflow-hidden bg-[linear-gradient(160deg,rgba(15,23,42,0.92),rgba(30,41,59,0.68))] lg:flex">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(96,165,250,0.3),transparent_30%),radial-gradient(circle_at_80%_30%,rgba(168,85,247,0.22),transparent_32%),radial-gradient(circle_at_60%_75%,rgba(56,189,248,0.16),transparent_28%)]"></div>
                        <div class="relative flex w-full flex-col justify-between p-12 xl:p-16">
                            <div class="max-w-xl">
                                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-blue-200/75">GadgetFlow</p>
                                <h2 class="mt-6 text-5xl font-semibold leading-tight tracking-tight text-white">{{ $panelText }}</h2>
                                <p class="mt-6 max-w-lg text-base leading-7 text-slate-300">
                                    Unlock a polished rental experience built for modern gadget lovers, with seamless booking, fast verification, and a dashboard designed to keep every device within reach.
                                </p>
                            </div>

                            <div class="relative mt-12 flex flex-1 items-center justify-center">
                                <div class="absolute h-80 w-80 rounded-full bg-blue-500/15 blur-3xl"></div>
                                <div class="absolute h-72 w-72 rounded-full border border-white/10"></div>
                                <div class="relative w-full max-w-2xl">
                                    <div class="absolute left-6 top-12 h-40 w-56 rounded-[2rem] border border-white/15 bg-white/10 shadow-[0_25px_60px_rgba(15,23,42,0.35)] backdrop-blur-xl transition duration-500 hover:-translate-y-1 hover:bg-white/15"></div>
                                    <div class="absolute right-8 top-0 h-56 w-64 rounded-[2rem] border border-white/15 bg-gradient-to-br from-blue-400/20 to-violet-400/10 p-5 shadow-[0_30px_80px_rgba(59,130,246,0.18)] backdrop-blur-xl transition duration-500 hover:-translate-y-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.25em] text-blue-100/70">Featured</p>
                                                <p class="mt-2 text-lg font-semibold text-white">Creator Kit</p>
                                            </div>
                                            <span class="rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-200">Live</span>
                                        </div>
                                        <div class="mt-10 grid grid-cols-3 gap-3">
                                            <div class="h-20 rounded-2xl bg-white/10"></div>
                                            <div class="h-20 rounded-2xl bg-white/5"></div>
                                            <div class="h-20 rounded-2xl bg-white/10"></div>
                                        </div>
                                    </div>
                                    <div class="relative mx-auto mt-24 w-[22rem] rounded-[2.25rem] border border-white/15 bg-slate-900/80 p-4 shadow-[0_40px_120px_rgba(15,23,42,0.55)] backdrop-blur-xl transition duration-500 hover:-translate-y-2">
                                        <div class="rounded-[1.8rem] border border-white/10 bg-[linear-gradient(160deg,rgba(15,23,42,0.95),rgba(37,99,235,0.38))] p-5">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-xs uppercase tracking-[0.24em] text-blue-100/60">Premium Device</p>
                                                    <p class="mt-2 text-2xl font-semibold text-white">Vision Pro</p>
                                                </div>
                                                <div class="h-12 w-12 rounded-2xl bg-white/10"></div>
                                            </div>
                                            <div class="mt-8 rounded-[1.4rem] border border-white/10 bg-white/5 p-4">
                                                <div class="flex items-center justify-between text-sm text-slate-300">
                                                    <span>Rental status</span>
                                                    <span class="text-emerald-300">Available now</span>
                                                </div>
                                                <div class="mt-4 h-2 rounded-full bg-white/10">
                                                    <div class="h-2 w-2/3 rounded-full bg-gradient-to-r from-cyan-400 via-blue-400 to-violet-400"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 right-10 h-48 w-40 rounded-[2rem] border border-white/10 bg-white/10 shadow-2xl shadow-violet-950/20 backdrop-blur-xl transition duration-500 hover:-translate-y-1"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 text-slate-200">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-xl">
                                    <p class="text-2xl font-semibold text-white">24/7</p>
                                    <p class="mt-1 text-sm text-slate-400">Instant booking flow</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-xl">
                                    <p class="text-2xl font-semibold text-white">500+</p>
                                    <p class="mt-1 text-sm text-slate-400">Premium gadgets</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-xl">
                                    <p class="text-2xl font-semibold text-white">4.9/5</p>
                                    <p class="mt-1 text-sm text-slate-400">Member satisfaction</p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </body>
</html>
