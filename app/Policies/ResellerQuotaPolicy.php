<?php

namespace App\Policies;

use App\Models\ResellerQuota;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResellerQuotaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function view(User $user, ResellerQuota $quota)
    {
        return $user->is_admin || $user->id === $quota->reseller_id;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, ResellerQuota $quota)
    {
        return $user->is_admin;
    }

    public function delete(User $user, ResellerQuota $quota)
    {
        return $user->is_admin;
    }
}
