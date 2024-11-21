<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $authUser = auth()->user();
        $targetUser = $this->route('user');

        $this->validateStore();

        $this->validateUpdate($authUser, $targetUser);

        $this->validateDestroy($authUser, $targetUser);

        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'firstName' => 'required|string|min:3|max:50',
                'lastName' => 'required|string|min:3|max:50',
                'email' => 'required|email|regex:/^.+@.+\\..+$/i|max:100|unique:users',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string',
                'role' => 'required|in:user,manager,admin',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'firstName' => 'sometimes|required|string|min:3|max:50',
                'lastName' => 'sometimes|required|string|min:3|max:50',
                'email' => 'sometimes|required|email|regex:/^.+@.+\\..+$/i|max:100|unique:users,email,' . $this->user->id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'password_confirmation' => 'nullable|string',
                'role' => 'nullable|in:user,manager,admin',
            ];
        }

        return [];
    }

    public function messages(): array
    {
        return [
            'firstName.required' => 'O primeiro nome é obrigatório.',
            'lastName.required' => 'O último nome é obrigatório.',
            'email.unique' => 'O email já está em uso.',
            'password.confirmed' => 'As senhas não correspondem.',
            'role.in' => 'A role deve ser user, manager ou admin.',
        ];
    }

    private function validateUpdate($authUser, $targetUser)
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($authUser->id !== $targetUser->id) {
                if ($authUser->role === 'user')
                    throw new AuthorizationException('Usuários não podem editar outros usuários.');

                if ($targetUser->role === 'admin')
                    throw new AuthorizationException('Apenas um Administrador pode editar o próprio registro.');
            }

            if ($this->has('role') && $this->input('role') === 'admin' && $authUser->role === 'manager') {
                throw new AuthorizationException('Gerentes não podem atribuir a role de admin a usuários.');
            }

            if ($authUser->role === 'user' && $this->has('role') && $this->input('role') !== 'user') {
                throw new AuthorizationException('Usuários não podem atribuir a roles.');
            }
        }
    }

    private function validateStore()
    {
        if ($this->isMethod('post')) {
            $adminExists = User::where('role', 'admin')->exists();

            if ($adminExists) {
                $user = Auth::guard('sanctum')->user();

                if ($user->role === 'user') {
                    throw new AuthorizationException('Somente administradores ou gerentes podem cadastrar usuários.');
                }

                if ($user->role === 'manager' && $this->input('role') === 'admin') {
                    throw new AuthorizationException('Gerentes não podem criar administradores.');
                }
            } else {
                $this->merge(['role' => 'admin']);
            }
        }
    }
    
    private function validateDestroy($authUser, $targetUser)
    {
        if ($authUser->role === 'user')
            throw new AuthorizationException('Usuários não podem deletar usuários.');

        if ($authUser->id !== $targetUser->id && $targetUser->role === 'admin')
            throw new AuthorizationException('Apenas um Administrador pode deletar o próprio registro.');
    }
}
