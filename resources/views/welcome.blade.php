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
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600|space-grotesk:500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans text-white antialiased">
        <div class="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,0.22),_transparent_28%),radial-gradient(circle_at_85%_10%,_rgba(34,211,238,0.16),_transparent_24%),linear-gradient(145deg,_#020617_0%,_#0B1220_42%,_#030712_100%)]">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-20 top-14 h-72 w-72 rounded-full bg-indigo/20 blur-3xl"></div>
                <div class="absolute right-0 top-24 h-80 w-80 rounded-full bg-cyan/15 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-indigo/10 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:72px_72px] opacity-20"></div>
            </div>

            <div class="relative mx-auto min-h-screen max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center justify-between py-6 sm:py-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 shadow-lg shadow-indigo-950/30 backdrop-blur-xl">
                            <x-application-logo class="h-7 w-7" />
                        </span>
                        <span>
                            <span class="block font-display text-lg font-semibold tracking-[0.18em] text-indigo-100">GadgetFlow</span>
                            <span class="block font-body text-xs uppercase tracking-[0.3em] text-slate-400">Premium Rentals</span>
                        </span>
                    </a>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="rounded-full px-4 py-2 font-body text-sm font-medium text-slate-200 transition duration-300 hover:bg-white/5 hover:text-white">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="rounded-full border border-white/70 bg-white px-5 py-2 font-body text-sm font-semibold text-slate-950 shadow-[0_16px_40px_rgba(255,255,255,0.12)] transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100">
                            Register
                        </a>
                    </div>
                </nav>

                <main class="pb-20 pt-6 sm:pb-24 lg:pb-28">
                    <section class="grid items-center gap-12 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] lg:gap-16 lg:pt-10">
                        <div class="scroll-reveal max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/15 bg-white/8 px-4 py-2 font-body text-xs font-semibold uppercase tracking-[0.32em] text-cyan-100/80 backdrop-blur-xl">
                                <span class="h-2 w-2 rounded-full bg-cyan"></span>
                                Gadget marketplace, elevated
                            </div>

                            <h1 class="mt-6 font-display text-5xl font-semibold tracking-tight text-white sm:text-6xl lg:text-7xl">
                                Rent Premium Gadgets Anytime
                            </h1>

                            <p class="mt-6 max-w-xl font-body text-base leading-8 text-slate-300 sm:text-lg">
                                Browse smartphones, laptops, cameras, headphones, and gaming devices for hourly or daily rental.
                            </p>

                            <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                                <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-indigo to-cyan px-7 py-3.5 font-body text-sm font-semibold text-white shadow-[0_24px_60px_rgba(79,70,229,0.35)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_28px_70px_rgba(34,211,238,0.4)]">
                                    Browse Gadgets
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-7 py-3.5 font-body text-sm font-semibold text-slate-100 backdrop-blur-xl transition duration-300 hover:-translate-y-0.5 hover:bg-white/10">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white px-7 py-3.5 font-body text-sm font-semibold text-slate-950 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100">
                                    Register
                                </a>
                            </div>

                            <div class="mt-10 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_24px_60px_rgba(2,6,23,0.35)] backdrop-blur-xl">
                                    <p class="font-display text-2xl font-semibold text-white">500+</p>
                                    <p class="mt-1 font-body text-sm text-slate-400">Premium devices on rotation</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_24px_60px_rgba(2,6,23,0.35)] backdrop-blur-xl">
                                    <p class="font-display text-2xl font-semibold text-white">24/7</p>
                                    <p class="mt-1 font-body text-sm text-slate-400">Access for urgent projects</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_24px_60px_rgba(2,6,23,0.35)] backdrop-blur-xl">
                                    <p class="font-display text-2xl font-semibold text-white">Fast</p>
                                    <p class="mt-1 font-body text-sm text-slate-400">Pickup, walk-in, or delivery</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo/20 blur-3xl"></div>
                            <div class="absolute right-8 top-12 h-48 w-48 rounded-full bg-cyan/20 blur-3xl"></div>

                            <div class="relative grid gap-5 sm:grid-cols-2 lg:grid-cols-2">
                                @foreach ($heroImages as $file => $meta)
                                    @if (file_exists(public_path('images/sequence/' . $file)))
                                        <div class="scroll-reveal {{ $meta['classes'] }} rounded-[2rem] border border-white/10 bg-white/10 p-4 shadow-[0_30px_80px_rgba(15,23,42,0.45)] backdrop-blur-xl transition duration-500 hover:-translate-y-2 hover:bg-white/15">
                                            <div class="flex items-center justify-between font-body text-xs uppercase tracking-[0.25em] text-slate-300">
                                                <span>{{ $meta['tag'] }}</span>
                                                <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] text-indigo-100">Featured</span>
                                            </div>
                                            <div class="mt-4 overflow-hidden rounded-[1.6rem] bg-slate-950/60 p-4">
                                                <img
                                                    src="{{ asset('images/sequence/' . $file) }}"
                                                    alt="{{ $meta['label'] }}"
                                                    class="h-52 w-full object-contain transition duration-500 hover:scale-105"
                                                >
                                            </div>
                                            <div class="mt-4">
                                                <p class="font-display text-lg font-semibold text-white">{{ $meta['label'] }}</p>
                                                <p class="mt-1 font-body text-sm text-slate-400">Flexible rental timing for work, travel, and entertainment.</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="scroll-reveal rounded-[2rem] border border-white/10 bg-gradient-to-br from-indigo/15 via-transparent to-cyan/15 p-5 shadow-[0_30px_80px_rgba(15,23,42,0.45)] backdrop-blur-xl sm:col-span-2">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="font-body text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200/80">Why GadgetFlow</p>
                                            <p class="mt-3 max-w-xl font-display text-2xl font-semibold tracking-tight text-white">Premium hardware, polished access, and a smoother way to rent on demand.</p>
                                        </div>
                                        <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/10 px-5 py-3 font-body text-sm font-semibold text-white transition duration-300 hover:bg-white/15">
                                            Explore inventory
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-24 sm:mt-28">
                        <div class="scroll-reveal max-w-2xl">
                            <p class="font-body text-sm font-semibold uppercase tracking-[0.3em] text-cyan-200/70">Features</p>
                            <h2 class="mt-4 font-display text-3xl font-semibold tracking-tight text-white sm:text-4xl">Rental convenience designed like a premium product.</h2>
                        </div>

                        <div class="mt-10 grid gap-5 lg:grid-cols-3">
                            <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-7 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/15 font-display text-indigo-100">01</div>
                                <h3 class="mt-6 font-display text-2xl font-semibold text-white">Hourly / Daily Rentals</h3>
                                <p class="mt-3 font-body text-sm leading-7 text-slate-300">Choose short or extended access based on your project, trip, event, or testing needs.</p>
                            </article>
                            <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-7 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan/15 font-display text-cyan-100">02</div>
                                <h3 class="mt-6 font-display text-2xl font-semibold text-white">Walk-in or Delivery</h3>
                                <p class="mt-3 font-body text-sm leading-7 text-slate-300">Reserve for pickup or streamline access with delivery options that keep your schedule moving.</p>
                            </article>
                            <article class="scroll-reveal rounded-[2rem] border border-white/10 bg-white/8 p-7 shadow-[0_30px_80px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/15 font-display text-indigo-100">03</div>
                                <h3 class="mt-6 font-display text-2xl font-semibold text-white">Easy Payment Tracking</h3>
                                <p class="mt-3 font-body text-sm leading-7 text-slate-300">Keep payment status clear from request to return with a simple, transparent rental flow.</p>
                            </article>
                        </div>
                    </section>

                    <section class="mt-24 sm:mt-28">
                        <div class="scroll-reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-2xl">
                                <p class="font-body text-sm font-semibold uppercase tracking-[0.3em] text-cyan-200/70">Showcase</p>
                                <h2 class="mt-4 font-display text-3xl font-semibold tracking-tight text-white sm:text-4xl">Curated gadgets ready for work, travel, creativity, and play.</h2>
                            </div>
                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 font-body text-sm font-semibold text-slate-100 backdrop-blur-xl transition duration-300 hover:bg-white/10">
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
                                            <h3 class="font-display text-xl font-semibold text-white">{{ $meta['title'] }}</h3>
                                            <p class="mt-2 font-body text-sm leading-7 text-slate-400">{{ $meta['copy'] }}</p>
                                        </div>
                                    </article>
                                @endif
                            @endforeach
                        </div>
                    </section>

                    <section class="mt-24 sm:mt-28">
                        <div class="scroll-reveal rounded-[2.5rem] border border-white/10 bg-[linear-gradient(145deg,rgba(15,23,42,0.92),rgba(79,70,229,0.22),rgba(34,211,238,0.18))] p-8 shadow-[0_40px_100px_rgba(15,23,42,0.45)] backdrop-blur-xl sm:p-10 lg:p-12">
                            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                                <div class="max-w-2xl">
                                    <p class="font-body text-sm font-semibold uppercase tracking-[0.3em] text-cyan-200/70">Get Started</p>
                                    <h2 class="mt-4 font-display text-3xl font-semibold tracking-tight text-white sm:text-4xl">Start renting smarter with GadgetFlow</h2>
                                    <p class="mt-4 font-body text-base leading-8 text-slate-300">Move from browsing to booking with a cleaner gadget rental experience built for flexibility and speed.</p>
                                </div>

                                <div class="flex flex-col gap-4 sm:flex-row">
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-6 py-3.5 font-body text-sm font-semibold text-white backdrop-blur-xl transition duration-300 hover:bg-white/10">
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white px-6 py-3.5 font-body text-sm font-semibold text-slate-950 transition duration-300 hover:bg-slate-100">
                                        Register
                                    </a>
                                    <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-indigo to-cyan px-6 py-3.5 font-body text-sm font-semibold text-white shadow-[0_24px_60px_rgba(79,70,229,0.35)] transition duration-300 hover:-translate-y-0.5">
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

                const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

                elements.forEach(function (element) {
                    observer.observe(element);

                    // IntersectionObserver's first callback isn't guaranteed to fire before the
                    // next paint, which can read as a blank page for content already in the
                    // viewport on load. Reveal anything already on-screen immediately instead of
                    // waiting on that async callback; the observer will still keep it in sync
                    // (including re-hiding it) as the user scrolls from here.
                    const rect = element.getBoundingClientRect();

                    if (rect.top < viewportHeight && rect.bottom > 0) {
                        element.classList.remove('opacity-0', 'translate-y-8');
                        element.classList.add('opacity-100', 'translate-y-0');
                    }
                });
            });
        </script>
    </body>
</html>
