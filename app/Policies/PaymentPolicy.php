<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->is_admin || $user->is_reseller || $user->is_customer;
    }

    public function view(User $user, Payment $payment)
    {
        return $user->is_admin || $user->id === $payment->purchase->buyer_id;
    }

    public function create(User $user)
    {
        // Payments are created by system only
        return false;
    }

    public function update(User $user, Payment $payment)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Payment $payment)
    {
        return $user->is_admin;
    }

    public function before(User $user, $ability)
{
    if ($user->isAdmin()) {
        return true;
    }
}
}
