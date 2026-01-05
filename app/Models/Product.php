<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',             // wp_plugin, wp_theme, flutter_app
        'description',
        'current_version',
        'update_url',
        'price',
        'supports_trial',
        'trial_days',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'description'     => 'string',
        'current_version' => 'string',
        'update_url'      => 'string',
        'price'           => 'integer',         // unsignedBigInteger
        'supports_trial'  => 'boolean',
        'trial_days'      => 'integer',
        'is_active'       => 'boolean',
        'metadata'        => 'array',           // JSON cast to array
    ];

    // Relationships
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSupportsTrial($query)
    {
        return $query->where('supports_trial', true);
    }
}