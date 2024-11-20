<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            return $this->response('Autenticado', 200, [
                'token' => $request->user()->createToken('token', [$request->user()->role])->plainTextToken
            ]);
        }
        return $this->response('Não autorizado', 403);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Token revogado', 200);
    }
}
