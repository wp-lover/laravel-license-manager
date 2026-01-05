<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'buyer_id',
        'product_id',
        'quantity',
        'total_amount',
        'currency',
        'status',
        'purchased_at',
    ];

    protected $casts = [
        'total_amount'   => 'decimal:2',
        'purchased_at'   => 'datetime',
        'status'         => 'string', // pending, completed, refunded, canceled
        'currency'       => 'string',
        'quantity'       => 'integer',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }
}