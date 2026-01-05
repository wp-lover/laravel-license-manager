<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerQuota extends Model
{
    protected $fillable = [
        'reseller_id',
        'free_license_limit',
        'used_free_licenses',
    ];

    protected $casts = [
        'free_license_limit' => 'integer',
        'used_free_licenses' => 'integer',
    ];

    // Relationship
    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    // Helper methods
    public function hasAvailableQuota(): bool
    {
        return $this->used_free_licenses < $this->free_license_limit;
    }

    public function availableQuota(): int
    {
        return $this->free_license_limit - $this->used_free_licenses;
    }

    public function isQuotaExhausted(): bool
    {
        return $this->used_free_licenses >= $this->free_license_limit;
    }

    // Scope to find resellers with available quota
    public function scopeWithAvailableQuota($query)
    {
        return $query->whereRaw('used_free_licenses < free_license_limit');
    }
}