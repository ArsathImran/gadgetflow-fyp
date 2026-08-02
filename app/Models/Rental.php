<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gadget_id',
        'bundle_id',
        'qr_token',
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
        'rental_amount_received',
        'deposit_amount_received',
        'payment_shortfall_notes',
        'shipping_status',
        'start_date',
        'end_date',
        'total_amount',
        'points_redeemed',
        'discount_amount',
        'status',
        'returned_at',
        'ending_soon_notified_at',
        'condition_on_return',
        'return_notes',
        'deposit_amount',
        'deposit_status',
        'deposit_refund_amount',
        'deposit_deduction_reason',
        'late_fee_amount',
        'late_fee_waived',
        'payment_collected_at',
        'payment_collected_by',
        'handed_over_at',
    ];

    protected function casts(): array
    {
        return [
            'agreement_accepted' => 'boolean',
            'payment_proofs' => 'array',
            'returned_at' => 'datetime',
            'ending_soon_notified_at' => 'datetime',
            'discount_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'rental_amount_received' => 'decimal:2',
            'deposit_amount_received' => 'decimal:2',
            'deposit_refund_amount' => 'decimal:2',
            'late_fee_amount' => 'decimal:2',
            'late_fee_waived' => 'boolean',
            'payment_collected_at' => 'datetime',
            'handed_over_at' => 'datetime',
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

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function collectedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_collected_by');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function itemName(): string
    {
        return $this->gadget?->name
            ?? $this->bundle?->name
            ?? 'Unknown Rental Item';
    }

    public function isBundle(): bool
    {
        return $this->bundle_id !== null;
    }
}
