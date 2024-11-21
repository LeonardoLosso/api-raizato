<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fornecedor;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    use HttpResponses;


    public function index()
    {
        $fornecedores = Fornecedor::all();

        return $this->response('Ok', 200, [$fornecedores]);
    }

    public function show(string $id)
    {
        $fornecedor = Fornecedor::find($id);

        if (!$fornecedor) {
            return $this->error('Not Found', 404);
        }

        return $this->response('Ok', 200, [$fornecedor]);
    }

    public function store(Request $request)
    {
        $this->authorize('userDeny', User::class);

        $validated = $request->validate([
            'nome' => 'required|string|min:3|max:100',
            'cnpj' => 'required|string|size:14|unique:fornecedores,cnpj',
            'contato' => 'nullable|string|max:100',
        ]);

        $fornecedor = Fornecedor::create($validated);

        return $this->response('Created', 201, [$fornecedor]);
    }
}
