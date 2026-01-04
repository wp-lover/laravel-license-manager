<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    protected $fillable = [
        'purchase_id', 'product_id', 'owner_id', 'sold_by_user_id',
        'license_key', 'domain', 'status', 'type', 'activated_at', 'expires_at'
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_by_user_id');
    }

    // Check if license is usable
    public function isUsable(): bool
    {
        return $this->type !== 'unpaid'
            && $this->status === 'active'
            && (! $this->expires_at || $this->expires_at->isFuture());
    }
}
