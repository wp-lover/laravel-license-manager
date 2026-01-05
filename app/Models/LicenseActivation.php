<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseActivation extends Model
{
    protected $table = 'license_activations';

    protected $fillable = [
        'license_id',
        'identifier',
        'type',
        'ip_address',
        'user_agent',
        'activated_at',
        'last_checked_at',
        'revoked_at',
    ];

    protected $casts = [
        'activated_at'    => 'datetime',
        'last_checked_at' => 'datetime',
        'revoked_at'      => 'datetime',
        'type'            => 'string', // domain or app
    ];

    // Relationships
    public function license()
    {
        return $this->belongsTo(License::class);
    }

    // Helper methods
    public function isActive(): bool
    {
        return is_null($this->revoked_at);
    }

    public function isRevoked(): bool
    {
        return !is_null($this->revoked_at);
    }

    // Scope for active activations
    public function scopeActive($query)
    {
        return $query->whereNull('revoked_at');
    }

    public function scopeRevoked($query)
    {
        return $query->whereNotNull('revoked_at');
    }

    public function scopeDomain($query)
    {
        return $query->where('type', 'domain');
    }

    public function scopeApp($query)
    {
        return $query->where('type', 'app');
    }
}