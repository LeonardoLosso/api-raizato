<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('userDeny', auth()->user());
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
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
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.string' => 'O nome da categoria deve ser um texto.',
            'name.max' => 'O nome da categoria não pode ter mais que 50 caracteres.',
            'description.string' => 'A descrição da categoria deve ser um texto.',
            'description.max' => 'A descrição da categoria não pode ter mais que 500 caracteres.',
        ];
    }
}
