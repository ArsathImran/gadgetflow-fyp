<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'daily_rental_price',
        'hourly_rental_price',
        'deposit_amount',
        'late_fee_per_day',
        'image',
        'status',
    ];
}
