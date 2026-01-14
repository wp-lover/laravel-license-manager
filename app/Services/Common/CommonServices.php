<?php

namespace App\Services\Common;

use App\Models\User;

class CommonServices
{

    public static function currentUser(): User
    {
        return auth()->user();
    }

    public static function createUser($email): User
    {
        return User::create([
            'name'  => explode('@', $email)[0], // e.g., "john" from john@gmail.com
            'email' => $email,
            'password' => 'default-password'
        ]);
    }
}
