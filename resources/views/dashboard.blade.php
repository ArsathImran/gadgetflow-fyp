<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                {{ auth()->user()->isAdmin() ? __('Admin Dashboard') : __('My Dashboard') }}
            </h2>
            <p class="font-body text-sm text-slate">
                {{ auth()->user()->isAdmin() ? 'Track rentals, inventory, and revenue at a glance.' : 'Stay on top of your rentals, due dates, and spending.' }}
            </p>
        </div>
    </x-slot>

    @php
        $user = auth()->user();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($user->isAdmin())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <p class="font-body text-sm font-medium text-slate">Total Gadgets</p>
                        <p class="mt-4 font-display text-5xl font-bold text-ink">{{ $totalGadgetsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <p class="font-body text-sm font-medium text-slate">Active Rentals</p>
                        <p class="mt-4 font-display text-5xl font-bold text-ink">{{ $activeRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-[0_18px_40px_rgba(245,158,11,0.10)]">
                        <p class="font-body text-sm font-medium text-amber-700">Pending Approvals</p>
                        <p class="mt-4 font-display text-5xl font-bold text-amber-900">{{ $pendingApprovalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-6 shadow-[0_18px_40px_rgba(79,70,229,0.10)]">
                        <p class="font-body text-sm font-medium text-indigo-700">Pending Payments</p>
                        <p class="mt-4 font-display text-5xl font-bold text-indigo-900">{{ $pendingPaymentsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-[0_18px_40px_rgba(251,191,36,0.12)]">
                        <p class="font-body text-sm font-medium text-amber-700">Overdue Rentals</p>
                        <p class="mt-4 font-display text-5xl font-bold text-amber-900">{{ $overdueRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-[0_18px_40px_rgba(16,185,129,0.10)]">
                        <p class="font-body text-sm font-medium text-emerald-700">Revenue This Month</p>
                        <p class="mt-4 font-display text-5xl font-bold text-emerald-900">{{ number_format((float) $revenueThisMonth, 2) }}</p>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,1fr)]">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display text-lg font-semibold text-ink">Rentals Per Month</h3>
                                <p class="mt-1 font-body text-sm text-slate">New rentals created over the last six months.</p>
                            </div>
                        </div>
                        <div class="mt-6 h-80">
                            <canvas id="rentalsPerMonthChart"></canvas>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-display text-lg font-semibold text-ink">Low Stock Gadgets</h3>
                                    <p class="mt-1 font-body text-sm text-slate">Items that may need restocking soon.</p>
                                </div>
                            </div>
                            <div class="mt-6 space-y-4">
                                @forelse ($lowStockGadgets as $gadget)
                                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-cloud px-4 py-3">
                                        <div>
                                            <p class="font-display font-semibold text-ink">{{ $gadget->name }}</p>
                                            <p class="font-body text-sm text-slate">{{ $gadget->category?->name ?? 'Uncategorized' }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 font-mono text-sm font-semibold text-rose-700">
                                            {{ $gadget->quantity }} left
                                        </span>
                                    </div>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 font-body text-sm text-slate">
                                        No low stock gadgets right now.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-display text-lg font-semibold text-ink">Top Rented Gadgets</h3>
                                    <p class="mt-1 font-body text-sm text-slate">Most requested devices across all rentals.</p>
                                </div>
                            </div>
                            <div class="mt-6 space-y-4">
                                @forelse ($topRentedGadgets as $gadget)
                                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                        <div>
                                            <p class="font-display font-semibold text-ink">{{ $gadget->name }}</p>
                                            <p class="font-body text-sm text-slate">{{ $gadget->category?->name ?? 'Uncategorized' }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full bg-cyan-100 px-3 py-1 font-mono text-sm font-semibold text-cyan-700">
                                            {{ $gadget->rentals_count }} rentals
                                        </span>
                                    </div>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 font-body text-sm text-slate">
                                        No rental activity recorded yet.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                    <div class="flex flex-col gap-2">
                        <h3 class="font-display text-lg font-semibold text-ink">Reports</h3>
                        <p class="font-body text-sm text-slate">Export filtered rental records or download a monthly revenue summary.</p>
                    </div>

                    <div class="mt-6 grid gap-6 xl:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-cloud p-5">
                            <h4 class="font-display text-base font-semibold text-ink">Rentals CSV</h4>
                            <p class="mt-1 font-body text-sm text-slate">Filter rentals by created date and status before exporting.</p>

                            <form method="GET" action="{{ route('admin.reports.rentals-csv') }}" class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="report-from" class="font-body text-sm font-medium text-slate-700">From</label>
                                    <input
                                        id="report-from"
                                        type="date"
                                        name="from"
                                        class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div>
                                    <label for="report-to" class="font-body text-sm font-medium text-slate-700">To</label>
                                    <input
                                        id="report-to"
                                        type="date"
                                        name="to"
                                        class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="report-status" class="font-body text-sm font-medium text-slate-700">Status</label>
                                    <select
                                        id="report-status"
                                        name="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled_by_customer">Cancelled by Customer</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <button type="submit" class="inline-flex items-center rounded-md bg-ink px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-slate-900">
                                        Download CSV
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-cloud p-5">
                            <h4 class="font-display text-base font-semibold text-ink">Revenue PDF</h4>
                            <p class="mt-1 font-body text-sm text-slate">Generate a downloadable monthly summary for finance reporting.</p>

                            <form method="GET" action="{{ route('admin.reports.revenue-pdf') }}" class="mt-5 flex flex-col gap-3">
                                <div>
                                    <label for="report-month" class="font-body text-sm font-medium text-slate-700">Month</label>
                                    <input
                                        id="report-month"
                                        type="month"
                                        name="month"
                                        value="{{ now()->format('Y-m') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div>
                                    <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-emerald-500">
                                        Download Revenue PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.12)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 12 4l8.25 4.25M3.75 8.25v8.5L12 21l8.25-4.25v-8.5M3.75 8.25 12 12.5l8.25-4.25M12 12.5V21" />
                            </svg>
                        </span>
                        <p class="mt-5 font-body text-sm font-medium text-slate">Active Rentals</p>
                        <p class="mt-1 font-display text-5xl font-bold text-ink">{{ $activeRentalsCount }}</p>
                    </div>
                    <div class="group rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-[0_18px_40px_rgba(251,191,36,0.12)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(251,191,36,0.18)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <circle cx="12" cy="12.5" r="8" stroke-linecap="round" stroke-linejoin="round" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2" />
                            </svg>
                        </span>
                        <p class="mt-5 font-body text-sm font-medium text-amber-700">Overdue Rentals</p>
                        <p class="mt-1 font-display text-5xl font-bold text-amber-900">{{ $overdueRentalsCount }}</p>
                    </div>
                    <div class="group rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-[0_18px_40px_rgba(16,185,129,0.10)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(16,185,129,0.16)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 7.5A2 2 0 0 1 5.5 5.5h11a2 2 0 0 1 2 2v1h1.5a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-9Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 13.25a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5Z" />
                            </svg>
                        </span>
                        <p class="mt-5 font-body text-sm font-medium text-emerald-700">Total Spent</p>
                        <p class="mt-1 font-display text-5xl font-bold text-emerald-900">{{ number_format((float) $totalSpent, 2) }}</p>
                    </div>
                    <a href="{{ route('customer.rewards.index') }}" class="group rounded-3xl border border-indigo-200 bg-indigo-50 p-6 shadow-[0_18px_40px_rgba(79,70,229,0.10)] transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-[0_24px_50px_rgba(79,70,229,0.16)]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m12 3.5 2.47 5.06 5.53.8-4 3.9.94 5.5L12 16.2l-4.94 2.56.94-5.5-4-3.9 5.53-.8L12 3.5Z" />
                            </svg>
                        </span>
                        <p class="mt-5 font-body text-sm font-medium text-indigo-700">Loyalty Points</p>
                        <p class="mt-1 font-display text-5xl font-bold text-indigo-900">{{ number_format($user->loyalty_points) }}</p>
                        <p class="mt-2 font-body text-xs font-semibold uppercase tracking-wide text-indigo-700">{{ ucfirst($user->currentTier()) }} Tier</p>
                    </a>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1.4fr)_minmax(280px,0.9fr)]">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display text-lg font-semibold text-ink">Upcoming Due Dates</h3>
                                <p class="mt-1 font-body text-sm text-slate">Your next rentals that need attention.</p>
                            </div>
                        </div>
                        <div class="mt-6 space-y-4">
                            @forelse ($upcomingDueDates as $rental)
                                @php
                                    $thumbnail = $rental->isBundle() ? $rental->bundle?->image : $rental->gadget?->image;
                                    $isOverdue = $rental->isOverdue();
                                @endphp
                                <div class="flex items-center gap-4 rounded-2xl border px-4 py-4 transition hover:-translate-y-0.5 {{ $isOverdue ? 'border-amber-200 bg-amber-50 hover:shadow-[0_16px_30px_rgba(251,191,36,0.16)]' : 'border-slate-200 bg-cloud hover:shadow-[0_16px_30px_rgba(15,23,42,0.08)]' }}">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
                                        @if ($thumbnail)
                                            <img src="{{ asset('storage/' . $thumbnail) }}" alt="{{ $rental->itemName() }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 12 4l8.25 4.25M3.75 8.25v8.5L12 21l8.25-4.25v-8.5M3.75 8.25 12 12.5l8.25-4.25M12 12.5V21" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="truncate font-display font-semibold text-ink">{{ $rental->itemName() }}</p>
                                            <span class="inline-flex shrink-0 rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                            </span>
                                        </div>
                                        <p class="mt-1 font-body text-sm text-slate">Due on {{ $rental->end_date }}</p>
                                    </div>
                                    @if ($isOverdue)
                                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-amber-500 px-3 py-1 font-body text-xs font-semibold text-white">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 3.5h.01M10.29 3.86 1.82 18a1.5 1.5 0 0 0 1.29 2.25h17.78A1.5 1.5 0 0 0 22.18 18L13.71 3.86a1.5 1.5 0 0 0-2.42 0Z" />
                                            </svg>
                                            {{ $rental->daysOverdue() }}d overdue
                                        </span>
                                    @else
                                        <span class="inline-flex shrink-0 rounded-full bg-blue-100 px-3 py-1 font-body text-xs font-semibold text-blue-700">
                                            On track
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 font-body text-sm text-slate">
                                    No active rentals with upcoming due dates right now.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <h3 class="font-display text-lg font-semibold text-ink">Quick Actions</h3>
                        <p class="mt-1 font-body text-sm text-slate">Jump straight to the pages you use most.</p>
                        <div class="mt-6 flex flex-col gap-3">
                            <a href="{{ route('customer.gadgets.index') }}" class="group flex items-center gap-3 rounded-xl bg-ink px-4 py-3 text-sm font-body font-semibold text-white transition hover:bg-slate-900">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/10">
                                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <circle cx="10.5" cy="10.5" r="6.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 20l-4.35-4.35" />
                                    </svg>
                                </span>
                                Browse Gadgets
                                <svg class="ml-auto h-4 w-4 text-white/50 transition group-hover:translate-x-0.5 group-hover:text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ route('customer.rentals.index') }}" class="group flex items-center gap-3 rounded-xl bg-indigo px-4 py-3 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/15">
                                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <rect x="5" y="4" width="14" height="17" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path stroke-linecap="round" d="M9 9h6M9 13h6M9 17h3" />
                                    </svg>
                                </span>
                                View My Rentals
                                <svg class="ml-auto h-4 w-4 text-white/50 transition group-hover:translate-x-0.5 group-hover:text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- How It Works --}}
                <div class="mt-16">
                    <div class="text-center">
                        <p class="font-body text-xs font-semibold uppercase tracking-[0.22em] text-indigo">Simple Process</p>
                        <h2 class="mt-3 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">How It Works</h2>
                        <p class="mx-auto mt-3 max-w-xl font-body text-sm text-slate">Renting your next gadget takes four easy steps, from browsing to hand-back.</p>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($steps as $step)
                            <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                                        @switch($step['icon'])
                                            @case('search')
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <circle cx="10.5" cy="10.5" r="6.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 20l-4.35-4.35" />
                                                </svg>
                                                @break
                                            @case('calendar')
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <rect x="3.5" y="5" width="17" height="15" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path stroke-linecap="round" d="M3.5 9.5h17M8 3v3M16 3v3" />
                                                </svg>
                                                @break
                                            @case('card')
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <rect x="3" y="6" width="18" height="13" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path stroke-linecap="round" d="M3 10.5h18" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 15h4" />
                                                </svg>
                                                @break
                                            @case('qr')
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <rect x="3.5" y="3.5" width="6" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" />
                                                    <rect x="14.5" y="3.5" width="6" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" />
                                                    <rect x="3.5" y="14.5" width="6" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 14.5h3v3h-3zM20.5 14.5v2M17.5 20.5h3" />
                                                </svg>
                                                @break
                                        @endswitch
                                    </span>
                                    <span class="font-mono text-xs font-medium text-slate-400">{{ $step['number'] }}</span>
                                </div>
                                <p class="mt-5 font-display text-lg font-semibold text-ink">{{ $step['title'] }}</p>
                                <p class="mt-2 font-body text-sm leading-6 text-slate">{{ $step['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($testimonials->isNotEmpty())
                    {{-- Testimonials --}}
                    <div class="mt-16">
                        <div class="text-center">
                            <p class="font-body text-xs font-semibold uppercase tracking-[0.22em] text-indigo">Testimonials</p>
                            <h2 class="mt-3 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">What Our Customers Say</h2>
                        </div>

                        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                            @foreach ($testimonials as $testimonial)
                                <div class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_50px_rgba(15,23,42,0.10)]">
                                    <div class="flex items-center gap-0.5">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <span class="{{ $star <= $testimonial->rating ? 'text-amber-500' : 'text-amber-200' }}">&#9733;</span>
                                        @endfor
                                    </div>
                                    <p class="mt-4 flex-1 font-body text-sm leading-6 text-slate-700">&ldquo;{{ $testimonial->comment }}&rdquo;</p>
                                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-4">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-sm font-semibold text-white">
                                            {{ strtoupper(substr($testimonial->user->name ?? '?', 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-display text-sm font-semibold text-ink">{{ $testimonial->user->name ?? 'Verified Renter' }}</p>
                                            <p class="font-body text-xs text-slate">{{ $testimonial->gadget->name ?? $testimonial->bundle->name ?? 'GadgetFlow Rental' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if ($user->isAdmin())
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.3/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const chartElement = document.getElementById('rentalsPerMonthChart');

                if (!chartElement) {
                    return;
                }

                const rentalLabels = @json(collect($rentalsPerMonth)->pluck('label'));
                const rentalCounts = @json(collect($rentalsPerMonth)->pluck('count'));

                new Chart(chartElement, {
                    type: 'bar',
                    data: {
                        labels: rentalLabels,
                        datasets: [{
                            label: 'Rentals',
                            data: rentalCounts,
                            backgroundColor: '#4F46E5',
                            borderRadius: 12,
                            maxBarThickness: 48,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                },
                                grid: {
                                    color: '#e2e8f0',
                                },
                            },
                            x: {
                                grid: {
                                    display: false,
                                },
                            },
                        },
                    },
                });
            });
        </script>
    @endif
</x-app-layout>
