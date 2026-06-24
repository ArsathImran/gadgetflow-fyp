<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function gadgets(): HasMany
    {
        return $this->hasMany(Gadget::class);
    }
}
