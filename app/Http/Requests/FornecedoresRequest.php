<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class FornecedoresRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('userDeny', auth()->user());
    }

    public function rules(): array
    {
        if ($this->isMethod('post'))
            return [
                'nome' => 'required|string|min:3|max:100',
                'cnpj' => 'required|string|size:14|unique:fornecedores,cnpj',
                'contato' => 'nullable|string|max:100',
            ];
        if ($this->isMethod('put') || $this->isMethod('patch'))
            return [
                'nome' => 'required|string|min:3|max:100',
                'cnpj' => 'required|string|size:14|unique:fornecedores,cnpj,' . $this->id,
                'contato' => 'nullable|string|max:100',
            ];
    }
    /**
     * Retorna as mensagens de erro personalizadas para validação.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do fornecedor é obrigatório.',
            'nome.string' => 'O nome do fornecedor deve ser um texto.',
            'nome.min' => 'O nome do fornecedor deve ter no mínimo 3 caracteres.',
            'nome.max' => 'O nome do fornecedor não pode ter mais que 100 caracteres.',
            'cnpj.required' => 'O CNPJ do fornecedor é obrigatório.',
            'cnpj.string' => 'O CNPJ do fornecedor deve ser um texto.',
            'cnpj.size' => 'O CNPJ do fornecedor deve ter exatamente 14 caracteres.',
            'cnpj.unique' => 'O CNPJ informado já está cadastrado.',
            'contato.string' => 'O contato do fornecedor deve ser um texto.',
            'contato.max' => 'O contato do fornecedor não pode ter mais que 100 caracteres.',
        ];
    }
}
