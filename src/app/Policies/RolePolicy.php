<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return true;  // for all
    }

    public function create(User $user)
    {
        return $user->roles->contains(function ($role) {
            return $role->name === 'admin';
        });
    }

    public function update(User $user)
    {
        return $user->roles->contains(function ($role) {
            return $role->name === 'admin';
        });
    }

    public function delete(User $user)
    {
        return $user->roles->contains(function ($role) {
            return $role->name === 'admin';
        });
    }
}
