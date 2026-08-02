@php
    $openInquiriesCount = Auth::user()->isAdmin() ? \App\Models\Inquiry::where('status', 'open')->count() : 0;
    $pendingRentalActionsCount = Auth::user()->isAdmin()
        ? \App\Models\Rental::query()
            ->where(function ($query) {
                $query->where('status', 'pending')->orWhere('payment_status', 'pending');
            })
            ->count()
        : 0;
@endphp

<nav x-data="{ open: false }" class="bg-ink border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto" />
                        <span class="font-display text-lg font-semibold tracking-tight text-white">GadgetFlow</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('gadgets.index')" :active="request()->routeIs('gadgets.*')">
                            {{ __('Gadgets') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.bundles.index')" :active="request()->routeIs('admin.bundles.*')">
                            {{ __('Bundles') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.rentals.index')" :active="request()->routeIs('admin.rentals.*')">
                            {{ __('Rental Requests') }}
                            @if ($pendingRentalActionsCount > 0)
                                <span class="ml-1.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 font-mono text-[10px] font-semibold text-white">
                                    {{ $pendingRentalActionsCount }}
                                </span>
                            @endif
                        </x-nav-link>
                        <x-nav-link :href="route('admin.scan')" :active="request()->routeIs('admin.scan*')">
                            {{ __('Scan QR') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
                            {{ __('Customers') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.inquiries.index')" :active="request()->routeIs('admin.inquiries.*')">
                            {{ __('Inquiries') }}
                            @if ($openInquiriesCount > 0)
                                <span class="ml-1.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 font-mono text-[10px] font-semibold text-white">
                                    {{ $openInquiriesCount }}
                                </span>
                            @endif
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customer.gadgets.index')" :active="request()->routeIs('customer.gadgets.*')">
                            {{ __('Browse Gadgets') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customer.rentals.index')" :active="request()->routeIs('customer.rentals.*')">
                            {{ __('My Rentals') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customer.rewards.index')" :active="request()->routeIs('customer.rewards.*')">
                            {{ __('Rewards') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customer.bundles.index', ['type' => 'wedding'])" :active="request()->routeIs('customer.bundles.*') && request('type') === 'wedding'">
                            {{ __('Wedding Combo') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customer.bundles.index', ['type' => 'short_film'])" :active="request()->routeIs('customer.bundles.*') && request('type') === 'short_film'">
                            {{ __('Short Film Combo') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="w-56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 rounded-full border border-transparent bg-white/5 py-1.5 ps-1.5 pe-3 text-sm leading-4 font-body font-medium text-slate-200 transition ease-in-out duration-150 hover:bg-white/10 hover:text-white focus:outline-none">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-sm font-semibold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (Auth::user()->isAdmin())
                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('about')">
                                {{ __('About Us') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('contact')">
                                {{ __('Contact') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('customer.inquiries.index')">
                                {{ __('My Inquiries') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-300 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden bg-ink sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('gadgets.index')" :active="request()->routeIs('gadgets.*')">
                    {{ __('Gadgets') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.bundles.index')" :active="request()->routeIs('admin.bundles.*')">
                    {{ __('Bundles') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.rentals.index')" :active="request()->routeIs('admin.rentals.*')">
                    {{ __('Rental Requests') }}
                    @if ($pendingRentalActionsCount > 0)
                        <span class="ml-1.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 font-mono text-[10px] font-semibold text-white">
                            {{ $pendingRentalActionsCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.scan')" :active="request()->routeIs('admin.scan*')">
                    {{ __('Scan QR') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
                    {{ __('Customers') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.inquiries.index')" :active="request()->routeIs('admin.inquiries.*')">
                    {{ __('Inquiries') }}
                    @if ($openInquiriesCount > 0)
                        <span class="ml-1.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 font-mono text-[10px] font-semibold text-white">
                            {{ $openInquiriesCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.gadgets.index')" :active="request()->routeIs('customer.gadgets.*')">
                    {{ __('Browse Gadgets') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.rentals.index')" :active="request()->routeIs('customer.rentals.*')">
                    {{ __('My Rentals') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.rewards.index')" :active="request()->routeIs('customer.rewards.*')">
                    {{ __('Rewards') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.bundles.index', ['type' => 'wedding'])" :active="request()->routeIs('customer.bundles.*') && request('type') === 'wedding'">
                    {{ __('Wedding Combo') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.bundles.index', ['type' => 'short_film'])" :active="request()->routeIs('customer.bundles.*') && request('type') === 'short_film'">
                    {{ __('Short Film Combo') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
                    {{ __('About Us') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                    {{ __('Contact') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.inquiries.index')" :active="request()->routeIs('customer.inquiries.*')">
                    {{ __('My Inquiries') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="flex items-center gap-3 px-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-base font-semibold text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div>
                    <div class="font-body font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-body font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
