<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'gadget_id',
        'start_date',
        'end_date',
        'total_amount',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gadget(): BelongsTo
    {
        return $this->belongsTo(Gadget::class);
    }
}
