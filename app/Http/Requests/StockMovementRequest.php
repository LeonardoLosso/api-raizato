<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $this->setUser();
        return true;
    }

    public function rules(): array
    {

        if ($this->isMethod('post')) {
            return [
                'product_id' => 'required|exists:products,id',
                'movement_type' => 'required|in:compras,devolucoes,vendas,perdas',
                'quantity' => 'required|numeric',
                'unit_price' => 'required|numeric',
                'description' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'user_id' => 'required|exists:users,id',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'product_id' => 'required|exists:products,id',
                'movement_type' => 'required|in:compras,devolucoes,vendas,perdas',
                'quantity' => 'required|numeric',
                'unit_price' => 'required|numeric',
                'description' => 'nullable|string|max:255',
                'date' => 'nullable|date',
            ];
        }
    }
    public function messages(): array
    {
        return [
            'product_id.required' => 'O campo produto é obrigatório.',
            'product_id.exists' => 'O produto selecionado não existe.',
            'movement_type.required' => 'O tipo de movimento é obrigatório.',
            'movement_type.in' => 'O tipo de movimento deve ser uma das opções: compras, devoluções, vendas, ou perdas.',
            'quantity.required' => 'A quantidade é obrigatória.',
            'quantity.numeric' => 'A quantidade deve ser um número.',
            'unit_price.required' => 'O preço unitário é obrigatório.',
            'unit_price.numeric' => 'O preço unitário deve ser um número.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais que 255 caracteres.',
            'date.date' => 'A data deve ser uma data válida.',
            'user_id.required' => 'O campo usuário é obrigatório.',
            'user_id.exists' => 'O usuário selecionado não existe.',
        ];
    }
    private function setUser()
    {
        if ($this->isMethod('post')) {
            $user = Auth::guard('sanctum')->user();
            $this->merge(['user_id' => $user->id]);
        }
    }
    protected function prepareForValidation()
    {
        $this->merge(
            collect($this->all())->mapWithKeys(function ($value, $key) {
                return [Str::snake($key) => $value];
            })->toArray()
        );
    }
}
