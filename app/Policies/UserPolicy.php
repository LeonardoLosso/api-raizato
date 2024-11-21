<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function userDeny(User $authUser)
    {
        return in_array($authUser->role, ['admin', 'manager']);
    }
}
