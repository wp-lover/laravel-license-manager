<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->is_admin || $user->is_reseller || $user->is_customer;
    }

    public function view(User $user, Purchase $purchase)
    {
        return $user->is_admin || $user->id === $purchase->buyer_id;
    }

    public function create(User $user)
    {
        // Only reseller or customer can create purchase
        return $user->is_reseller || $user->is_customer;
    }

    public function update(User $user, Purchase $purchase)
    {
        // Only admin can update a purchase status
        return $user->is_admin;
    }

    public function delete(User $user, Purchase $purchase)
    {
        // Only admin can delete
        return $user->is_admin;
    }

    public function before(User $user, $ability)
{
    if ($user->isAdmin()) {
        return true;
    }
}
}
