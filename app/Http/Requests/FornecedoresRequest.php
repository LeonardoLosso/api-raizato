<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class FornecedoresRequest extends FormRequest
{
    public function authorize(): bool
    {
        $authUser = auth()->user();

        if (Gate::allows('userDeny', $authUser)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|min:3|max:100',
            'cnpj' => 'required|string|size:14|unique:fornecedores,cnpj',
            'contato' => 'nullable|string|max:100',
        ];
    }
}