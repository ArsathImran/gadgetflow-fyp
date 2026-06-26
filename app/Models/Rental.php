<?php

namespace App\Models;

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
        'payment_status',
        'shipping_status',
        'start_date',
        'end_date',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'agreement_accepted' => 'boolean',
        ];
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
