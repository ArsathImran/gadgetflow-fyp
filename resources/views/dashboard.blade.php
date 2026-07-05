<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ auth()->user()->isAdmin() ? __('Admin Dashboard') : __('My Dashboard') }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ auth()->user()->isAdmin() ? 'Track rentals, inventory, and revenue at a glance.' : 'Stay on top of your rentals, due dates, and spending.' }}
            </p>
        </div>
    </x-slot>

    @php
        $user = auth()->user();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            @else
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <p class="text-sm font-medium text-slate-500">Active Rentals</p>
                        <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $activeRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-orange-200 bg-orange-50 p-6 shadow-[0_18px_40px_rgba(249,115,22,0.10)]">
                        <p class="text-sm font-medium text-orange-700">Overdue Rentals</p>
                        <p class="mt-4 text-4xl font-semibold text-orange-900">{{ $overdueRentalsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-[0_18px_40px_rgba(16,185,129,0.10)]">
                        <p class="text-sm font-medium text-emerald-700">Total Spent</p>
                        <p class="mt-4 text-4xl font-semibold text-emerald-900">{{ number_format((float) $totalSpent, 2) }}</p>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1.4fr)_minmax(280px,0.9fr)]">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-950">Upcoming Due Dates</h3>
                                <p class="mt-1 text-sm text-slate-500">Your next rentals that need attention.</p>
                            </div>
                        </div>
                        <div class="mt-6 space-y-4">
                            @forelse ($upcomingDueDates as $rental)
                                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $rental->gadget?->name ?? 'Unknown Gadget' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">Due on {{ $rental->end_date }}</p>
                                    </div>
                                    @if ($rental->isOverdue())
                                        <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-sm font-semibold text-orange-700">
                                            {{ $rental->daysOverdue() }} day{{ $rental->daysOverdue() === 1 ? '' : 's' }} overdue
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                            Active
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-sm text-slate-500">
                                    No active rentals with upcoming due dates right now.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <h3 class="text-lg font-semibold text-slate-950">Quick Actions</h3>
                        <p class="mt-1 text-sm text-slate-500">Jump straight to the pages you use most.</p>
                        <div class="mt-6 flex flex-col gap-3">
                            <a href="{{ route('customer.gadgets.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                Browse Gadgets
                            </a>
                            <a href="{{ route('customer.rentals.index') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">
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
