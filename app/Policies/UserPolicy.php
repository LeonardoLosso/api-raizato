<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function restringeUser(User $authUser)
    {
        return in_array($authUser->role, ['admin', 'manager']);
    }

    public function update(User $authUser, User $targetUser)
    {
        if ($authUser->id === $targetUser->id && $authUser->role === 'admin') {
            return false;
        }

        if ($authUser->role === 'manager' && $targetUser->role === 'admin') {
            return false;
        }

        if ($authUser->role === 'manager' && $targetUser->role !== 'manager' && $targetUser->role !== 'user') {
            return false;
        }

        if ($authUser->id !== $targetUser->id && $authUser->role === 'user') {
            return false;
        }

        return true;
    }

    public function destroy(User $authUser, User $targetUser)
    {
        if ($authUser->id === $targetUser->id && $authUser->role === 'admin') {
            return false;
        }

        if ($authUser->role === 'manager' && $targetUser->role === 'admin') {
            return false;
        }

        if ($authUser->role === 'manager' && in_array($targetUser->role, ['user', 'manager'])) {
            return true;
        }

        if ($authUser->role === 'admin') {
            return true;
        }

        return false;
    }
}
