<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['index', 'show', 'update', 'destroy']);
        $this->middleware('check.admin.exists')->only(['store']);
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);

        return $this->response('Ok', 200, [
            UserResource::collection(User::all())
        ]);
    }
    
    public function store(Request $request)
    {
        $adminExists = User::where('role', 'admin')->exists();
        if ($adminExists) {
            $user = Auth::guard('sanctum')->user();

            if ($user->role === 'user') {
                return $this->error('Somente administradores ou gerentes podem cadastrar usuários.', 403);
            }
            if ($user->role === 'manager' && $request->input('role') === 'admin') {
                return $this->error('Gerentes não podem criar administradores.', 403);
            }
        }

        if (!$adminExists) {
            $request->merge(['role' => 'admin']);
        }

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|min:3|max:50',
            'lastName' => 'required|string|min:3|max:50',
            'email' => 'required|email|regex:/^.+@.+\\..+$/i|max:100|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|in:user,manager,admin',
        ]);
        if ($validator->fails()) {
            return $this->error('Falha na validação dos dados', 422, $validator->errors());
        }

        $created = User::create($validator->validated());
        if (!$created) {
            return $this->error('Erro ao criar usuário', 400);
        }
        return $this->response('Created', 201, [$created]);
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->response('Not Found', 404);
        }

        return $this->response('Ok', 200, [$user]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        if ($request->has('role') && $request->role === 'admin' && Auth::user()->role === 'manager') {
            return $this->error('Gerentes não podem atribuir a role de admin a usuários.', 403);
        }

        if ($request->has('role') && ($request->role === 'admin' || $request->role === 'manager') && Auth::user()->role === 'user') {
            return $this->error('Usuarios não podem atribuir a roles.', 403);
        }

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|min:3|max:50',
            'lastName' => 'required|string|min:3|max:50',
            'email' => 'required|email|regex:/^.+@.+\\..+$/i|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable',
            'role' => 'nullable|in:user,manager,admin'
        ]);

        if ($validator->fails()) {
            return $this->error('Falha na validação dos dados', 422, $validator->errors());
        }
        
        $user->update($request->all());

        return $this->response('Usuário atualizado com sucesso', 200, $user);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        return $this->response('No Content', 204, ['Usuário excluído com sucesso']);
    }
}
