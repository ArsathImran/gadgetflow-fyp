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
                        <p class="text-sm font-medium text-slate-500">Total Gadgets</p>
                        <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $totalGadgetsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <p class="text-sm font-medium text-slate-500">Active Rentals</p>
                        <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $activeRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-[0_18px_40px_rgba(245,158,11,0.10)]">
                        <p class="text-sm font-medium text-amber-700">Pending Approvals</p>
                        <p class="mt-4 text-4xl font-semibold text-amber-900">{{ $pendingApprovalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-blue-200 bg-blue-50 p-6 shadow-[0_18px_40px_rgba(59,130,246,0.10)]">
                        <p class="text-sm font-medium text-blue-700">Pending Payments</p>
                        <p class="mt-4 text-4xl font-semibold text-blue-900">{{ $pendingPaymentsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-orange-200 bg-orange-50 p-6 shadow-[0_18px_40px_rgba(249,115,22,0.10)]">
                        <p class="text-sm font-medium text-orange-700">Overdue Rentals</p>
                        <p class="mt-4 text-4xl font-semibold text-orange-900">{{ $overdueRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-[0_18px_40px_rgba(16,185,129,0.10)]">
                        <p class="text-sm font-medium text-emerald-700">Revenue This Month</p>
                        <p class="mt-4 text-4xl font-semibold text-emerald-900">{{ number_format((float) $revenueThisMonth, 2) }}</p>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,1fr)]">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-950">Rentals Per Month</h3>
                                <p class="mt-1 text-sm text-slate-500">New rentals created over the last six months.</p>
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
                                    <h3 class="text-lg font-semibold text-slate-950">Low Stock Gadgets</h3>
                                    <p class="mt-1 text-sm text-slate-500">Items that may need restocking soon.</p>
                                </div>
                            </div>
                            <div class="mt-6 space-y-4">
                                @forelse ($lowStockGadgets as $gadget)
                                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $gadget->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $gadget->category?->name ?? 'Uncategorized' }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-700">
                                            {{ $gadget->quantity }} left
                                        </span>
                                    </div>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">
                                        No low stock gadgets right now.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-950">Top Rented Gadgets</h3>
                                    <p class="mt-1 text-sm text-slate-500">Most requested devices across all rentals.</p>
                                </div>
                            </div>
                            <div class="mt-6 space-y-4">
                                @forelse ($topRentedGadgets as $gadget)
                                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $gadget->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $gadget->category?->name ?? 'Uncategorized' }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full bg-cyan-100 px-3 py-1 text-sm font-semibold text-cyan-700">
                                            {{ $gadget->rentals_count }} rentals
                                        </span>
                                    </div>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">
                                        No rental activity recorded yet.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                    <div class="flex flex-col gap-2">
                        <h3 class="text-lg font-semibold text-slate-950">Reports</h3>
                        <p class="text-sm text-slate-500">Export filtered rental records or download a monthly revenue summary.</p>
                    </div>

                    <div class="mt-6 grid gap-6 xl:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h4 class="text-base font-semibold text-slate-900">Rentals CSV</h4>
                            <p class="mt-1 text-sm text-slate-500">Filter rentals by created date and status before exporting.</p>

                            <form method="GET" action="{{ route('admin.reports.rentals-csv') }}" class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="report-from" class="text-sm font-medium text-slate-700">From</label>
                                    <input
                                        id="report-from"
                                        type="date"
                                        name="from"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div>
                                    <label for="report-to" class="text-sm font-medium text-slate-700">To</label>
                                    <input
                                        id="report-to"
                                        type="date"
                                        name="to"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="report-status" class="text-sm font-medium text-slate-700">Status</label>
                                    <select
                                        id="report-status"
                                        name="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                    <button type="submit" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                        Download CSV
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h4 class="text-base font-semibold text-slate-900">Revenue PDF</h4>
                            <p class="mt-1 text-sm text-slate-500">Generate a downloadable monthly summary for finance reporting.</p>

                            <form method="GET" action="{{ route('admin.reports.revenue-pdf') }}" class="mt-5 flex flex-col gap-3">
                                <div>
                                    <label for="report-month" class="text-sm font-medium text-slate-700">Month</label>
                                    <input
                                        id="report-month"
                                        type="month"
                                        name="month"
                                        value="{{ now()->format('Y-m') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div>
                                    <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">
                                        Download Revenue PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <p class="font-body text-sm font-medium text-slate">Active Rentals</p>
                        <p class="mt-4 font-display text-5xl font-bold text-ink">{{ $activeRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-[0_18px_40px_rgba(251,191,36,0.12)]">
                        <p class="font-body text-sm font-medium text-amber-700">Overdue Rentals</p>
                        <p class="mt-4 font-display text-5xl font-bold text-amber-900">{{ $overdueRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-[0_18px_40px_rgba(16,185,129,0.10)]">
                        <p class="font-body text-sm font-medium text-emerald-700">Total Spent</p>
                        <p class="mt-4 font-display text-5xl font-bold text-emerald-900">{{ number_format((float) $totalSpent, 2) }}</p>
                    </div>
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
                                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-cloud px-4 py-4">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="font-display font-semibold text-ink">{{ $rental->itemName() }}</p>
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {{ $rental->isBundle() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $rental->isBundle() ? 'Combo' : 'Gadget' }}
                                            </span>
                                        </div>
                                        <p class="mt-1 font-body text-sm text-slate">Due on {{ $rental->end_date }}</p>
                                    </div>
                                    @if ($rental->isOverdue())
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 font-body text-sm font-semibold text-amber-700">
                                            {{ $rental->daysOverdue() }} day{{ $rental->daysOverdue() === 1 ? '' : 's' }} overdue
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 font-body text-sm font-semibold text-blue-700">
                                            Active
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
                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-xl bg-ink px-4 py-3 text-sm font-body font-semibold text-white transition hover:bg-slate-900">
                                Browse Gadgets
                            </a>
                            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo px-4 py-3 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                View My Rentals
                            </a>
                        </div>
                    </div>
                </div>
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
                            backgroundColor: '#2563eb',
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
