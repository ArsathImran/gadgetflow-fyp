<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Gadget extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'daily_rental_price',
        'hourly_rental_price',
        'deposit_amount',
        'late_fee_per_day',
        'quantity',
        'image',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}
