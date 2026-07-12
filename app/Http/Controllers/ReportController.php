<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function rentalsCsv(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'status' => ['nullable', 'string'],
        ]);

        $filename = 'rentals-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($validated) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Rental ID',
                'Customer Name',
                'Customer Email',
                'Rental Item',
                'Rental Type',
                'Start Date',
                'End Date',
                'Status',
                'Payment Status',
                'Total Amount',
                'Deposit Amount',
                'Deposit Status',
                'Late Fee Amount',
                'Returned At',
            ]);

            Rental::query()
                ->with(['user', 'gadget', 'bundle'])
                ->when(! empty($validated['from']), function ($query) use ($validated) {
                    $query->whereDate('created_at', '>=', $validated['from']);
                })
                ->when(! empty($validated['to']), function ($query) use ($validated) {
                    $query->whereDate('created_at', '<=', $validated['to']);
                })
                ->when(! empty($validated['status']), function ($query) use ($validated) {
                    $query->where('status', $validated['status']);
                })
                ->orderBy('created_at')
                ->chunk(200, function ($rentals) use ($handle) {
                    foreach ($rentals as $rental) {
                        fputcsv($handle, [
                            $rental->id,
                            $rental->user?->name ?? '-',
                            $rental->user?->email ?? '-',
                            $rental->itemName(),
                            $rental->rental_type,
                            $rental->start_date,
                            $rental->end_date,
                            $rental->status,
                            $rental->payment_status,
                            number_format((float) $rental->total_amount, 2, '.', ''),
                            number_format((float) ($rental->deposit_amount ?? 0), 2, '.', ''),
                            $rental->deposit_status,
                            number_format((float) ($rental->late_fee_amount ?? 0), 2, '.', ''),
                            $rental->returned_at?->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function revenuePdf(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'PDF export is not available yet. Run "composer require barryvdh/laravel-dompdf" first.');
        }

        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $selectedMonth = $validated['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $monthlyRentalsQuery = Rental::query()
            ->whereBetween('rentals.created_at', [$monthStart, $monthEnd]);

        $completedMonthQuery = Rental::query()
            ->where('rentals.status', 'completed')
            ->whereBetween('rentals.returned_at', [$monthStart, $monthEnd]);

        $totalRevenue = (clone $completedMonthQuery)->sum('total_amount');
        $totalRentalsCount = (clone $monthlyRentalsQuery)->count();
        $totalLateFeesCollected = (clone $completedMonthQuery)->sum('late_fee_amount');

        $categoryBreakdown = (clone $monthlyRentalsQuery)
            ->join('gadgets', 'rentals.gadget_id', '=', 'gadgets.id')
            ->join('categories', 'gadgets.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(rentals.id) as rentals_count'),
                DB::raw("SUM(CASE WHEN rentals.status = 'completed' AND rentals.returned_at BETWEEN '{$monthStart->toDateTimeString()}' AND '{$monthEnd->toDateTimeString()}' THEN rentals.total_amount ELSE 0 END) as revenue")
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('rentals_count')
            ->get();

        $topGadgets = (clone $monthlyRentalsQuery)
            ->join('gadgets', 'rentals.gadget_id', '=', 'gadgets.id')
            ->select('gadgets.name', DB::raw('COUNT(rentals.id) as rentals_count'))
            ->groupBy('gadgets.id', 'gadgets.name')
            ->orderByDesc('rentals_count')
            ->limit(5)
            ->get();

        $depositSummary = [
            'held' => (clone $monthlyRentalsQuery)
                ->where(function ($query) {
                    $query->whereNull('rentals.deposit_status')
                        ->orWhere('rentals.deposit_status', 'held');
                })
                ->sum('rentals.deposit_amount'),
            'refunded' => (clone $completedMonthQuery)
                ->whereIn('rentals.deposit_status', ['refunded', 'partially_refunded'])
                ->sum(DB::raw('COALESCE(deposit_refund_amount, 0)')),
            'deducted' => (clone $completedMonthQuery)
                ->where('rentals.deposit_status', 'deducted')
                ->sum('rentals.deposit_amount'),
        ];

        $hasData = $totalRentalsCount > 0
            || (float) $totalRevenue > 0
            || (float) $totalLateFeesCollected > 0
            || $categoryBreakdown->isNotEmpty()
            || $topGadgets->isNotEmpty();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.revenue-pdf', [
            'selectedMonth' => $selectedMonth,
            'monthLabel' => $monthStart->format('F Y'),
            'totalRevenue' => $totalRevenue,
            'totalRentalsCount' => $totalRentalsCount,
            'totalLateFeesCollected' => $totalLateFeesCollected,
            'categoryBreakdown' => $categoryBreakdown,
            'topGadgets' => $topGadgets,
            'depositSummary' => $depositSummary,
            'hasData' => $hasData,
            'generatedAt' => now(),
        ]);

        return $pdf->download('revenue-report-' . $selectedMonth . '.pdf');
    }
}
