@php
    $heroImages = [
        '14inch-m4-macbook-pro-right-angle-3.webp' => ['label' => 'MacBook Pro', 'tag' => 'Creator Laptop', 'classes' => 'sm:ml-10 lg:ml-0 lg:mt-8'],
        'airpod_cups.JPG' => ['label' => 'AirPods Max', 'tag' => 'Premium Audio', 'classes' => 'sm:ml-auto lg:mr-6 lg:-mt-10'],
        'Gear-Nintendo-Switch-OLED-Julian-Chokkattu-1.webp' => ['label' => 'Nintendo Switch OLED', 'tag' => 'Gaming Console', 'classes' => 'sm:ml-24 lg:ml-16'],
    ];

    $showcaseImages = [
        '14inch-m4-macbook-pro-right-angle-3.webp' => ['title' => 'Laptop Power', 'copy' => 'High-performance devices for design, editing, and remote work.'],
        'airpod_cups.JPG' => ['title' => 'Immersive Audio', 'copy' => 'Noise-cancelling headphones that travel as well as you do.'],
        'Gear-Nintendo-Switch-OLED-Julian-Chokkattu-1.webp' => ['title' => 'Gaming Ready', 'copy' => 'Flexible rentals for casual sessions or full weekend play.'],
        'sony_headphones_1000xm3_1675507928_72ce4551.jpg' => ['title' => 'Sony Essentials', 'copy' => 'Reliable premium listening gear for focus, travel, and calls.'],
        'OIP.webp' => ['title' => 'Smart Tech', 'copy' => 'Popular devices curated for easy hourly or daily access.'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'GadgetFlow') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 text-white antialiased" style="font-family: 'Manrope', sans-serif;">
        <div class="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.2),_transparent_28%),radial-gradient(circle_at_85%_10%,_rgba(168,85,247,0.18),_transparent_24%),linear-gradient(145deg,_#020617_0%,_#0f172a_42%,_#030712_100%)]">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-20 top-14 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="absolute right-0 top-24 h-80 w-80 rounded-full bg-violet-500/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:72px_72px] opacity-20"></div>
            </div>

            <div class="relative mx-auto min-h-screen max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center justify-between py-6 sm:py-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 shadow-lg shadow-blue-950/30 backdrop-blur-xl">
                            <span class="text-lg font-extrabold tracking-[0.2em] text-white">GF</span>
                        </span>
                        <span>
                            <span class="block text-lg font-semibold tracking-[0.18em] text-blue-100">GadgetFlow</span>
                            <span class="block text-xs uppercase tracking-[0.3em] text-slate-400">Premium Rentals</span>
                        </span>
                    </a>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-medium text-slate-200 transition duration-300 hover:bg-white/5 hover:text-white">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="rounded-full border border-white/70 bg-white px-5 py-2 text-sm font-semibold text-slate-950 shadow-[0_16px_40px_rgba(255,255,255,0.12)] transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100">
                            Register
                        </a>
                    </div>
                </nav>

                <main class="pb-20 pt-6 sm:pb-24 lg:pb-28">
                    <section class="grid items-center gap-12 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] lg:gap-16 lg:pt-10">
                        <div class="scroll-reveal max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full border border-blue-300/15 bg-white/8 px-4 py-2 text-xs font-semibold uppercase tracking-[0.32em] text-blue-100/80 backdrop-blur-xl">
                                <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                                Gadget marketplace, elevated
                            </div>

                            <h1 class="mt-6 text-5xl font-semibold tracking-tight text-white sm:text-6xl lg:text-7xl">
                                Rent Premium Gadgets Anytime
                            </h1>

                            <p class="mt-6 max-w-xl text-base leading-8 text-slate-300 sm:text-lg">
                                Browse smartphones, laptops, cameras, headphones, and gaming devices for hourly or daily rental.
                            </p>

                            <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                                <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500 px-7 py-3.5 text-sm font-semibold text-white shadow-[0_24px_60px_rgba(59,130,246,0.35)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_28px_70px_rgba(99,102,241,0.45)]">
                                    Browse Gadgets
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-7 py-3.5 text-sm font-semibold text-slate-100 backdrop-blur-xl transition duration-300 hover:-translate-y-0.5 hover:bg-white/10">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white px-7 py-3.5 text-sm font-semibold text-slate-950 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100">
                                    Register
                                </a>
                            </div>

                            <div class="mt-10 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_24px_60px_rgba(2,6,23,0.35)] backdrop-blur-xl">
                                    <p class="text-2xl font-semibold text-white">500+</p>
                                    <p class="mt-1 text-sm text-slate-400">Premium devices on rotation</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_24px_60px_rgba(2,6,23,0.35)] backdrop-blur-xl">
                                    <p class="text-2xl font-semibold text-white">24/7</p>
                                    <p class="mt-1 text-sm text-slate-400">Access for urgent projects</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_24px_60px_rgba(2,6,23,0.35)] backdrop-blur-xl">
                                    <p class="text-2xl font-semibold text-white">Fast</p>
                                    <p class="mt-1 text-sm text-slate-400">Pickup, walk-in, or delivery</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>
                            <div class="absolute right-8 top-12 h-48 w-48 rounded-full bg-violet-500/20 blur-3xl"></div>

                            <div class="relative grid gap-5 sm:grid-cols-2 lg:grid-cols-2">
                                @foreach ($heroImages as $file => $meta)
                                    @if (file_exists(public_path('images/sequence/' . $file)))
                                        <div class="scroll-reveal {{ $meta['classes'] }} rounded-[2rem] border border-white/10 bg-white/10 p-4 shadow-[0_30px_80px_rgba(15,23,42,0.45)] backdrop-blur-xl transition duration-500 hover:-translate-y-2 hover:bg-white/15">
                                            <div class="flex items-center justify-between text-xs uppercase tracking-[0.25em] text-slate-300">
                                                <span>{{ $meta['tag'] }}</span>
                                                <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] text-blue-100">Featured</span>
                                            </div>
                                            <div class="mt-4 overflow-hidden rounded-[1.6rem] bg-slate-950/60 p-4">
                                                <img
                                                    src="{{ asset('images/sequence/' . $file) }}"
                                                    alt="{{ $meta['label'] }}"
                                                    class="h-52 w-full object-contain transition duration-500 hover:scale-105"
                                                >
                                            </div>
                                            <div class="mt-4">
                                                <p class="text-lg font-semibold text-white">{{ $meta['label'] }}</p>
                                                <p class="mt-1 text-sm text-slate-400">Flexible rental timing for work, travel, and entertainment.</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="scroll-reveal rounded-[2rem] border border-white/10 bg-gradient-to-br from-blue-500/15 via-transparent to-violet-500/15 p-5 shadow-[0_30px_80px_rgba(15,23,42,0.45)] backdrop-blur-xl sm:col-span-2">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-100/80">Why GadgetFlow</p>
                                            <p class="mt-3 max-w-xl text-2xl font-semibold tracking-tight text-white">Premium hardware, polished access, and a smoother way to rent on demand.</p>
                                        </div>
                                        <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-white/15">
                                            Explore inventory
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-24 sm:mt-28">
                        <div class="scroll-reveal max-w-2xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-blue-100/70">Features</p>
                            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">Rental convenience designed like a premium product.</h2>
                        </div>

                        <div class="mt-10 grid gap-5 lg:grid-cols-3">
                            <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-7 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500/15 text-blue-100">01</div>
                                <h3 class="mt-6 text-2xl font-semibold text-white">Hourly / Daily Rentals</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-300">Choose short or extended access based on your project, trip, event, or testing needs.</p>
                            </article>
                            <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-7 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-500/15 text-violet-100">02</div>
                                <h3 class="mt-6 text-2xl font-semibold text-white">Walk-in or Delivery</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-300">Reserve for pickup or streamline access with delivery options that keep your schedule moving.</p>
                            </article>
                            <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-7 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/15 text-cyan-100">03</div>
                                <h3 class="mt-6 text-2xl font-semibold text-white">Easy Payment Tracking</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-300">Keep payment status clear from request to return with a simple, transparent rental flow.</p>
                            </article>
                        </div>
                    </section>

                    <section class="mt-24 sm:mt-28">
                        <div class="scroll-reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-2xl">
                                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-blue-100/70">Showcase</p>
                                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">Curated gadgets ready for work, travel, creativity, and play.</h2>
                            </div>
                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 backdrop-blur-xl transition duration-300 hover:bg-white/10">
                                Browse all gadgets
                            </a>
                        </div>

                        <div class="mt-10 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($showcaseImages as $file => $meta)
                                @if (file_exists(public_path('images/sequence/' . $file)))
                                    <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-4 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl transition duration-500 hover:-translate-y-2 hover:bg-white/12">
                                        <div class="overflow-hidden rounded-[1.6rem] bg-slate-950/60">
                                            <img
                                                src="{{ asset('images/sequence/' . $file) }}"
                                                alt="{{ $meta['title'] }}"
                                                class="h-72 w-full object-cover transition duration-500 hover:scale-105"
                                            >
                                        </div>
                                        <div class="px-1 pb-2 pt-5">
                                            <h3 class="text-xl font-semibold text-white">{{ $meta['title'] }}</h3>
                                            <p class="mt-2 text-sm leading-7 text-slate-400">{{ $meta['copy'] }}</p>
                                        </div>
                                    </article>
                                @endif
                            @endforeach
                        </div>
                    </section>

                    <section class="mt-24 sm:mt-28">
                        <div class="scroll-reveal rounded-[2.5rem] border border-white/10 bg-[linear-gradient(145deg,rgba(15,23,42,0.92),rgba(37,99,235,0.2),rgba(91,33,182,0.22))] p-8 shadow-[0_40px_100px_rgba(15,23,42,0.45)] backdrop-blur-xl sm:p-10 lg:p-12">
                            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                                <div class="max-w-2xl">
                                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-blue-100/70">Get Started</p>
                                    <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">Start renting smarter with GadgetFlow</h2>
                                    <p class="mt-4 text-base leading-8 text-slate-300">Move from browsing to booking with a cleaner gadget rental experience built for flexibility and speed.</p>
                                </div>

                                <div class="flex flex-col gap-4 sm:flex-row">
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white backdrop-blur-xl transition duration-300 hover:bg-white/10">
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white px-6 py-3.5 text-sm font-semibold text-slate-950 transition duration-300 hover:bg-slate-100">
                                        Register
                                    </a>
                                    <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_24px_60px_rgba(59,130,246,0.35)] transition duration-300 hover:-translate-y-0.5">
                                        Browse Gadgets
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const elements = document.querySelectorAll('.scroll-reveal');

                elements.forEach(function (element) {
                    element.classList.add('opacity-0', 'translate-y-8', 'transition-all', 'duration-700', 'ease-out');
                });

                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.remove('opacity-0', 'translate-y-8');
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                        } else {
                            entry.target.classList.remove('opacity-100', 'translate-y-0');
                            entry.target.classList.add('opacity-0', 'translate-y-8');
                        }
                    });
                }, {
                    threshold: 0.18,
                    rootMargin: '0px 0px -8% 0px'
                });

                elements.forEach(function (element) {
                    observer.observe(element);
                });
            });
        </script>
    </body>
</html>
