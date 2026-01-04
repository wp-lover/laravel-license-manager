<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'supports_trial', 'trial_days',
    ];

    // A product has many purchases
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    // A product has many licenses through purchases
    public function licenses(): HasManyThrough
    {
        return $this->hasManyThrough(License::class, Purchase::class);
    }
}
