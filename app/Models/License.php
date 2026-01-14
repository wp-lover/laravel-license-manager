<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'owner_id',
        'sold_by_user_id',
        'license_key',
        'domain',
        'status',
        'max_domains',
        'type',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at'   => 'datetime',
        'status'       => 'string', // inactive, active, expired, revoked
        'type'         => 'string', // paid, trial, unpaid
    ];

    // Relationships
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function soldBy()
    {
        return $this->belongsTo(User::class, 'sold_by_user_id');
    }

    public function activations()
    {
        return $this->hasMany(LicenseActivation::class);
    }

    // Useful scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }

    public function scopePaid($query)
    {
        return $query->where('type', 'paid');
    }

    public function scopeTrial($query)
    {
        return $query->where('type', 'trial');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('type', 'unpaid');
    }

    // Helper to get current active activation (if any)
    public function currentActivation()
    {
        return $this->hasOne(LicenseActivation::class)->whereNull('revoked_at');
    }

    public function isUsable(): bool
    {
        return $this->type !== 'unpaid' &&
            $this->status === 'active' &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
