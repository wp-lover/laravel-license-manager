<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user) { return $user->is_admin; }
    public function view(User $user, Product $product) { return $user->is_admin; }
    public function create(User $user) { return $user->is_admin; }
    public function update(User $user, Product $product) { return $user->is_admin; }
    public function delete(User $user, Product $product) { return $user->is_admin; }

    public function before(User $user, $ability)
{
    if ($user->isAdmin()) {
        return true;
    }
}
}
