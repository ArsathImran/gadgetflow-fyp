<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revenue Report - {{ $monthLabel }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .muted {
            color: #6b7280;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .summary td {
            width: 33.33%;
            border: 1px solid #d1d5db;
            padding: 12px;
            vertical-align: top;
        }

        .summary .label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .summary .value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .notice {
            margin-bottom: 24px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 12px;
            color: #4b5563;
        }

        .section {
            margin-bottom: 24px;
        }

        .section h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.report-table th,
        table.report-table td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
        }

        table.report-table th {
            background: #f3f4f6;
            font-size: 11px;
            text-transform: uppercase;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Revenue Report</h1>
        <p class="muted">Reporting Month: {{ $monthLabel }}</p>
        <p class="muted">Generated At: {{ $generatedAt->format('Y-m-d H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Revenue</div>
                <div class="value">{{ number_format((float) $totalRevenue, 2) }}</div>
            </td>
            <td>
                <div class="label">Total Rentals</div>
                <div class="value">{{ $totalRentalsCount }}</div>
            </td>
            <td>
                <div class="label">Late Fees Collected</div>
                <div class="value">{{ number_format((float) $totalLateFeesCollected, 2) }}</div>
            </td>
        </tr>
    </table>

    @unless ($hasData)
        <div class="notice">
            No data for this period. The report is still generated successfully with zero totals.
        </div>
    @endunless

    <div class="section">
        <h2>Deposits Summary</h2>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Held</td>
                    <td>{{ number_format((float) $depositSummary['held'], 2) }}</td>
                </tr>
                <tr>
                    <td>Refunded</td>
                    <td>{{ number_format((float) $depositSummary['refunded'], 2) }}</td>
                </tr>
                <tr>
                    <td>Deducted</td>
                    <td>{{ number_format((float) $depositSummary['deducted'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Category Breakdown</h2>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Rentals Count</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categoryBreakdown as $category)
                    <tr>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->rentals_count }}</td>
                        <td>{{ number_format((float) $category->revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No category activity recorded for this month.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Top 5 Gadgets</h2>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Gadget</th>
                    <th>Rentals Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topGadgets as $gadget)
                    <tr>
                        <td>{{ $gadget->name }}</td>
                        <td>{{ $gadget->rentals_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No gadget activity recorded for this month.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
