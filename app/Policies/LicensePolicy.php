<?php

namespace App\Policies;

use App\Models\License;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LicensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // Admin can view all, reseller and customer see scoped licenses in Filament
        return $user->is_admin || $user->is_reseller || $user->is_customer;
    }

    public function view(User $user, License $license)
    {
        if ($user->is_admin) return true;
        if ($user->id === $license->owner_id) return true; // customer
        if ($user->is_reseller && ! $license->isUsable() && $user->id === $license->sold_by_user_id) return true;
        return false;
    }

    public function create(User $user)
    {
        // Admin and reseller can create licenses (reseller = free quota)
        return $user->is_admin || $user->is_reseller;
    }

    public function update(User $user, License $license)
    {
        // Admin can update all
        if ($user->is_admin) return true;

        // Reseller can update ONLY before license is usable
        if ($user->is_reseller && ! $license->isUsable() && $user->id === $license->sold_by_user_id) return true;

        return false;
    }

    public function activate(User $user, License $license)
    {
        // Only customer owner can activate
        return $user->id === $license->owner_id
            && $license->type !== 'unpaid'
            && $license->status === 'inactive';
    }

    public function assign(User $user, License $license)
    {
        // Reseller can assign license to a customer ONLY if unpaid and owner not set
        return $user->is_reseller
            && ! $license->owner_id
            && ! $license->isUsable();
    }

    public function changeDomain(User $user, License $license)
    {
        // Customer can change domain only if active and paid
        return $user->id === $license->owner_id
            && $license->status === 'active'
            && $license->type !== 'unpaid';
    }

    public function revoke(User $user, License $license)
    {
        // Admin can revoke any license
        if ($user->is_admin) return true;

        // Reseller can revoke ONLY unpaid licenses they created
        if ($user->is_reseller && ! $license->isUsable() && $user->id === $license->sold_by_user_id) return true;

        return false;
    }

    public function delete(User $user, License $license)
    {
        // Usually only admin deletes
        return $user->is_admin;
    }
}
