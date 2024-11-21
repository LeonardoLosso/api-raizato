<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;

/**
 * @OA\Tag(
 *     name="Usuários",
 *     description="Endpoints relacionados à gestão de usuários"
 * )
 */
class UserController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['index', 'show', 'update', 'destroy']);
        $this->middleware('check.admin.exists')->only(['store']);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/users",
     *     tags={"Usuários"},
     *     summary="Listar todos os usuários",
     *     description="Retorna uma lista de todos os usuários cadastrados.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function index()
    {
        $this->authorize('userDeny', User::class);

        return $this->response('Ok', 200, [
            UserResource::collection(User::all())
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/users",
     *     tags={"Usuários"},
     *     summary="Criar um novo usuário",
     *     description="Cria um novo usuário com base nos dados fornecidos.",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"firstName", "lastName", "email", "password", "password_confirmation", "role"},
     *             @OA\Property(property="firstName", type="string", description="Primeiro nome do usuário."),
     *             @OA\Property(property="lastName", type="string", description="Último nome do usuário."),
     *             @OA\Property(property="email", type="string", format="email", description="Email do usuário."),
     *             @OA\Property(property="phone", type="string", description="Telefone do usuário."),
     *             @OA\Property(property="password", type="string", description="Senha do usuário."),
     *             @OA\Property(property="password_confirmation", type="string", description="Confirmação da senha."),
     *             @OA\Property(property="role", type="string", enum={"user", "manager", "admin"}, description="Papel do usuário.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        $created = User::create($validatedData);
        if (!$created) {
            return $this->error('Erro ao criar usuário', 400);
        }
        return $this->response('Created', 201, [$created]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/users/{id}",
     *     tags={"Usuários"},
     *     summary="Exibir um usuário específico",
     *     description="Retorna os detalhes de um usuário com base no ID fornecido.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do usuário",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->response('Not Found', 404);
        }

        return $this->response('Ok', 200, [$user]);
    }

    /**
     * @OA\Put(
     *     path="/api/auth/users/{id}",
     *     tags={"Usuários"},
     *     summary="Atualizar um usuário",
     *     description="Atualiza os detalhes de um usuário com base no ID fornecido.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="firstName", type="string", description="Primeiro nome do usuário."),
     *             @OA\Property(property="lastName", type="string", description="Último nome do usuário."),
     *             @OA\Property(property="email", type="string", format="email", description="Email do usuário."),
     *             @OA\Property(property="phone", type="string", description="Telefone do usuário."),
     *             @OA\Property(property="password", type="string", description="Nova senha do usuário."),
     *             @OA\Property(property="password_confirmation", type="string", description="Confirmação da nova senha."),
     *             @OA\Property(property="role", type="string", enum={"user", "manager", "admin"}, description="Papel do usuário.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * 
     * )
     */
    public function update(UserRequest $request, User $user)
    {
        $validatedData  = $request->validated();

        $user->update($validatedData);

        return $this->response('Usuário atualizado com sucesso', 200, $user);
    }

    /**
     * @OA\Delete(
     *     path="/api/auth/users/{id}",
     *     tags={"Usuários"},
     *     summary="Excluir um usuário",
     *     description="Exclui um usuário com base no ID fornecido.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuário excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);

        $user->delete();
        return $this->response('No Content', 204);
    }
}
