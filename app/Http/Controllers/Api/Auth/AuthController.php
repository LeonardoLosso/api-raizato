<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Info(
     *     title="Controle de Estoque API",
     *     version="1.0.0",
     *     description="API para controle de estoque, movimentações e autenticação",
     *     termsOfService="http://example.com/terms/",
     *     contact={
     *         "name"="Leonardo Losso",
     *         "email"="leonardolosso.sz@gmail.com"
     *     },
     *     license={
     *         "name"="MIT",
     *         "url"="https://opensource.org/licenses/MIT"
     *     }
     * ),
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Autenticação"},
     *     summary="Autentica o usuário e gera um token",
     *     description="Este endpoint permite o login do usuário e retorna um token de autenticação.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", description="Email do usuário"),
     *             @OA\Property(property="password", type="string", description="Senha do usuário")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="Token de autenticação gerado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Credenciais inválidas",
     *     )
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->response('Autenticado', 200, [
                'token' => $request->user()->createToken('token', [$request->user()->role])->plainTextToken
            ]);
        }
        return $this->response('Não autorizado', 403);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Autenticação"},
     *     summary="Revoga o token do usuário",
     *     description="Este endpoint permite ao usuário sair da sessão, invalidando o token de autenticação.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token revogado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado, usuário não autenticado"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Token revogado', 200);
    }
}
