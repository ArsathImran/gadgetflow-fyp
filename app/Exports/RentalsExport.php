<?php

namespace App\Exports;

use App\Models\Rental;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RentalsExport implements FromQuery, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private const CURRENCY_FORMAT = '"RM"#,##0.00';

    public function __construct(private readonly array $filters)
    {
    }

    public function query(): Builder
    {
        return Rental::query()
            ->with(['user', 'gadget', 'bundle'])
            ->when(! empty($this->filters['from']), function ($query) {
                $query->whereDate('created_at', '>=', $this->filters['from']);
            })
            ->when(! empty($this->filters['to']), function ($query) {
                $query->whereDate('created_at', '<=', $this->filters['to']);
            })
            ->when(! empty($this->filters['status']), function ($query) {
                $query->where('status', $this->filters['status']);
            })
            ->orderBy('created_at');
    }

    public function headings(): array
    {
        return [
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
        ];
    }

    public function map($rental): array
    {
        return [
            $rental->id,
            $rental->user?->name ?? '-',
            $rental->user?->email ?? '-',
            $rental->itemName(),
            $rental->rental_type,
            $this->excelDate($rental->start_date),
            $this->excelDate($rental->end_date),
            $this->readableLabel($rental->status),
            $this->readableLabel($rental->payment_status),
            (float) $rental->total_amount,
            (float) ($rental->deposit_amount ?? 0),
            $this->readableLabel($rental->deposit_status),
            (float) ($rental->late_fee_amount ?? 0),
            $this->excelDate($rental->returned_at),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'J' => self::CURRENCY_FORMAT,
            'K' => self::CURRENCY_FORMAT,
            'M' => self::CURRENCY_FORMAT,
            'N' => 'yyyy-mm-dd hh:mm',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
            },
        ];
    }

    private function excelDate(mixed $date): ?float
    {
        if (empty($date)) {
            return null;
        }

        $date = $date instanceof \DateTimeInterface ? $date : \Carbon\Carbon::parse($date);

        return ExcelDate::dateTimeToExcel($date);
    }

    private function readableLabel(?string $value): string
    {
        if (empty($value)) {
            return '-';
        }

        $minorWords = ['by', 'of', 'to', 'in', 'for', 'and', 'the', 'a', 'an'];

        $words = explode(' ', str_replace('_', ' ', $value));

        return implode(' ', array_map(function ($word, $index) use ($minorWords) {
            if ($index > 0 && in_array(strtolower($word), $minorWords, true)) {
                return strtolower($word);
            }

            return ucfirst(strtolower($word));
        }, $words, array_keys($words)));
    }
}
