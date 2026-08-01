<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\Rental;
use App\Models\Review;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->check(), 403);

        $user = auth()->user();

        if ($user->isAdmin()) {
            $activeRentals = Rental::query()
                ->with(['gadget', 'bundle'])
                ->where('status', 'approved')
                ->whereNull('returned_at')
                ->get();

            $overdueRentalsCount = $activeRentals
                ->filter(fn (Rental $rental) => $rental->isOverdue())
                ->count();

            $startOfWindow = now()->startOfMonth()->subMonths(5);
            $monthlyRentalCounts = Rental::query()
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as aggregate')
                ->where('created_at', '>=', $startOfWindow)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at), MONTH(created_at)')
                ->get()
                ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->year, $row->month));

            $rentalsPerMonth = collect(range(0, 5))
                ->map(function (int $offset) use ($startOfWindow, $monthlyRentalCounts) {
                    $month = $startOfWindow->copy()->addMonths($offset);
                    $key = $month->format('Y-m');

                    return [
                        'label' => $month->format('M Y'),
                        'count' => (int) ($monthlyRentalCounts[$key]->aggregate ?? 0),
                    ];
                })
                ->all();

            return view('dashboard', [
                'totalGadgetsCount' => Gadget::query()->count(),
                'activeRentalsCount' => $activeRentals->count(),
                'pendingApprovalsCount' => Rental::query()->where('status', 'pending')->count(),
                'pendingPaymentsCount' => Rental::query()->where('payment_status', 'pending')->count(),
                'overdueRentalsCount' => $overdueRentalsCount,
                'revenueThisMonth' => Rental::query()
                    ->where('status', 'completed')
                    ->whereBetween('returned_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum('total_amount'),
                'lowStockGadgets' => Gadget::query()
                    ->with('category')
                    ->where('quantity', '<=', 2)
                    ->orderBy('quantity')
                    ->orderBy('name')
                    ->limit(5)
                    ->get(),
                'rentalsPerMonth' => $rentalsPerMonth,
                'topRentedGadgets' => Gadget::query()
                    ->with('category')
                    ->withCount('rentals')
                    ->orderByDesc('rentals_count')
                    ->orderBy('name')
                    ->limit(5)
                    ->get(),
            ]);
        }

        $activeRentals = Rental::query()
            ->with(['gadget', 'bundle'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereNull('returned_at')
            ->get();

        return view('dashboard', [
            'activeRentalsCount' => $activeRentals->count(),
            'upcomingDueDates' => Rental::query()
                ->with(['gadget', 'bundle'])
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereNull('returned_at')
                ->orderBy('end_date')
                ->limit(5)
                ->get(),
            'totalSpent' => Rental::query()
                ->where('user_id', $user->id)
                ->sum('total_amount'),
            'overdueRentalsCount' => $activeRentals
                ->filter(fn (Rental $rental) => $rental->isOverdue())
                ->count(),
            'steps' => [
                [
                    'number' => '01',
                    'icon' => 'search',
                    'title' => 'Browse & Choose',
                    'description' => 'Explore smartphones, laptops, cameras, and more to find the perfect gadget for your needs.',
                ],
                [
                    'number' => '02',
                    'icon' => 'calendar',
                    'title' => 'Request Your Rental',
                    'description' => 'Pick your dates, submit your details, and send a rental request in just a couple of clicks.',
                ],
                [
                    'number' => '03',
                    'icon' => 'card',
                    'title' => 'Get Approved & Pay',
                    'description' => 'Our admin team checks the request, then you settle payment securely to confirm the booking.',
                ],
                [
                    'number' => '04',
                    'icon' => 'qr',
                    'title' => 'Pickup / Return via QR Scan',
                    'description' => 'Show your unique QR code for a quick, verified handover and an equally smooth return.',
                ],
            ],
            'testimonials' => Review::query()
                ->with(['user', 'gadget', 'bundle'])
                ->where('rating', '>=', 4)
                ->whereNotNull('comment')
                ->where('comment', '!=', '')
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }
}
