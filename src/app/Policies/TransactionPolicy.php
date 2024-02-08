<?php

namespace App\Policies;

use App\Models\User;

class TransactionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->roles->contains(function ($role) {
            return $role->name === 'operator';
        });
    }

    public function update(User $user)
    {
        return $user->roles->contains(function ($role) {
            return $role->name === 'operator';
        });
    }

    public function delete(User $user)
    {
        return $user->roles->contains(function ($role) {
            return $role->name === 'operator';
        });
    }
}
