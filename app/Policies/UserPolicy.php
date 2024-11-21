<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserPolicy
{
    public function userDeny(User $authUser)
    {
        return in_array($authUser->role, ['admin', 'manager']);
    }

    public function destroy(User $authUser, User $targetUser)
    {
        if ($authUser->role === 'user')
            throw new AuthorizationException('Usuários não podem deletar usuários.');

        if ($authUser->id !== $targetUser->id && $targetUser->role === 'admin')
            throw new AuthorizationException('Apenas um Administrador pode deletar o próprio registro.');

        return true;
    }
}
