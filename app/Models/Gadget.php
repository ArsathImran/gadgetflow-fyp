<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gadget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand',
        'model',
        'name',
        'description',
        'daily_rental_price',
        'hourly_rental_price',
        'deposit_amount',
        'late_fee_per_day',
        'quantity',
        'image',
        'gallery_images',
        'status',
        'condition',
        'is_featured',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): ?float
    {
        $average = $this->reviews_avg_rating
            ?? ($this->relationLoaded('reviews')
                ? $this->reviews->avg('rating')
                : $this->reviews()->avg('rating'));

        return $average !== null ? round((float) $average, 1) : null;
    }

    public function reviewsCount(): int
    {
        if (isset($this->reviews_count)) {
            return (int) $this->reviews_count;
        }

        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }

        return $this->reviews()->count();
    }
}
