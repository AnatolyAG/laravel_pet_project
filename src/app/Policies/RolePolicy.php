<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

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
        return true;
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
