<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'gadget_id',
        'rental_type',
        'rental_hours',
        'pickup_type',
        'delivery_address',
        'phone_number',
        'ic_number',
        'agreement_accepted',
        'payment_proof',
        'payment_proofs',
        'payment_note',
        'payment_status',
        'shipping_status',
        'start_date',
        'end_date',
        'total_amount',
        'status',
        'returned_at',
        'condition_on_return',
        'return_notes',
        'deposit_amount',
        'deposit_status',
        'deposit_refund_amount',
        'deposit_deduction_reason',
        'late_fee_amount',
        'late_fee_waived',
    ];

    protected function casts(): array
    {
        return [
            'agreement_accepted' => 'boolean',
            'payment_proofs' => 'array',
            'returned_at' => 'datetime',
            'deposit_amount' => 'decimal:2',
            'deposit_refund_amount' => 'decimal:2',
            'late_fee_amount' => 'decimal:2',
            'late_fee_waived' => 'boolean',
        ];
    }

    public function isOverdue(): bool
    {
        if ($this->status !== 'approved' || $this->returned_at !== null || empty($this->end_date)) {
            return false;
        }

        return Carbon::parse($this->end_date)->endOfDay()->lt(now());
    }

    public function daysOverdue(): int
    {
        if (empty($this->end_date)) {
            return 0;
        }

        $dueDate = Carbon::parse($this->end_date)->startOfDay();
        $comparisonDate = ($this->returned_at ?? now())->copy()->startOfDay();

        if ($comparisonDate->lte($dueDate)) {
            return 0;
        }

        return $dueDate->diffInDays($comparisonDate);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gadget(): BelongsTo
    {
        return $this->belongsTo(Gadget::class);
    }
}
