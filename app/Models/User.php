<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // ← Must be fillable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // === Role check methods (camelCase) ===
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isReseller(): bool
    {
        return $this->role === 'reseller';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // === Filament panel access ===
    public function canAccessPanel(Panel $panel): bool
    {
        // During setup: allow all
        // return true;

        // Final: only admins access /admin panel
        return $this->isAdmin();

        // Or allow resellers too:
        // return $this->isAdmin() || $this->isReseller();
    }
}