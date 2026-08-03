<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revenue Report - {{ $monthLabel }}</title>
    <style>
        @page {
            margin: 0 0 60px 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        /* ---------- Header band ---------- */
        .header-band {
            background: #0B1220;
            padding: 22px 40px 20px 40px;
        }

        .header-band .brand {
            font-size: 26px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 0.3px;
        }

        .header-band .subtitle {
            font-size: 13px;
            color: #cbd5e1;
            margin-top: 2px;
        }

        .header-band .meta {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 10px;
        }

        .header-band .meta span {
            margin-right: 18px;
        }

        /* ---------- Page content ---------- */
        .content {
            padding: 24px 40px 0 40px;
        }

        /* ---------- Stat boxes ---------- */
        table.summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin: 0 -10px 22px -10px;
        }

        table.summary td {
            width: 33.33%;
            padding: 14px 16px;
            vertical-align: top;
            border: 1px solid #e5e7eb;
            border-left-width: 4px;
        }

        table.summary td.stat-revenue {
            border-left-color: #4F46E5;
            background: #EEF2FF;
        }

        table.summary td.stat-rentals {
            border-left-color: #64748B;
            background: #F1F5F9;
        }

        table.summary td.stat-fees {
            border-left-color: #FBBF24;
            background: #FFFBEB;
        }

        .summary .label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .summary .value {
            font-size: 22px;
            font-weight: bold;
            color: #111827;
        }

        /* ---------- Notice ---------- */
        .notice {
            margin-bottom: 22px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 12px;
            color: #4b5563;
        }

        /* ---------- Sections ---------- */
        .section {
            margin-bottom: 24px;
        }

        .section h2 {
            font-size: 14px;
            font-weight: bold;
            color: #0B1220;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #4F46E5;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.report-table th,
        table.report-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
        }

        table.report-table th {
            background: #4F46E5;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        table.report-table td.text-right,
        table.report-table th.text-right {
            text-align: right;
        }

        /* ---------- Footer ---------- */
        .footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 40px;
            padding: 10px 40px 0 40px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 9px;
        }

        .footer .pagenum:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="header-band">
        <div class="brand">GadgetFlow</div>
        <div class="subtitle">Revenue Report</div>
        <div class="meta">
            <span>Reporting Month: {{ $monthLabel }}</span>
            <span>Generated At: {{ $generatedAt->format('Y-m-d H:i') }}</span>
        </div>
    </div>

    <div class="content">
        <table class="summary">
            <tr>
                <td class="stat-revenue">
                    <div class="label">Total Revenue</div>
                    <div class="value">{{ number_format((float) $totalRevenue, 2) }}</div>
                </td>
                <td class="stat-rentals">
                    <div class="label">Total Rentals</div>
                    <div class="value">{{ $totalRentalsCount }}</div>
                </td>
                <td class="stat-fees">
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
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Held</td>
                        <td class="text-right">{{ number_format((float) $depositSummary['held'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Refunded</td>
                        <td class="text-right">{{ number_format((float) $depositSummary['refunded'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Deducted</td>
                        <td class="text-right">{{ number_format((float) $depositSummary['deducted'], 2) }}</td>
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
                        <th class="text-right">Rentals Count</th>
                        <th class="text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categoryBreakdown as $category)
                        <tr>
                            <td>{{ $category->category_name }}</td>
                            <td class="text-right">{{ $category->rentals_count }}</td>
                            <td class="text-right">{{ number_format((float) $category->revenue, 2) }}</td>
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
                        <th class="text-right">Rentals Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topGadgets as $gadget)
                        <tr>
                            <td>{{ $gadget->name }}</td>
                            <td class="text-right">{{ $gadget->rentals_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No gadget activity recorded for this month.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        Generated by GadgetFlow Admin Dashboard &middot; Page <span class="pagenum"></span>
    </div>
</body>
</html>
