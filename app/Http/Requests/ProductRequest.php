<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (in_array($this->method(), ['POST', 'PUT', 'PATCH']))
            return Gate::allows('userDeny', auth()->user());

        return true;
    }

    public function rules()
    {
        if (in_array($this->method(), ['POST'])) {
            return [
                'name' => 'required|string|max:50',
                'code' => 'required|string|unique:products,code',
                'description' => 'nullable|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'fornecedor_id' => 'required|exists:fornecedores,id',
                'cost_price' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'min_stock' => 'required|numeric',
                'expiry_date' => 'required|date',
            ];
        }
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'name' => 'required|string|max:50',
                'code' => 'required|string|unique:products,code,' . $this->id,
                'description' => 'nullable|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'fornecedor_id' => 'required|exists:fornecedores,id',
                'cost_price' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'min_stock' => 'required|numeric',
                'stock' => 'required|numeric',
                'expiry_date' => 'required|date',
            ];
        }
    }

    /**
     * Retorna as mensagens de erro personalizadas para validação.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.string' => 'O nome do produto deve ser um texto.',
            'name.max' => 'O nome do produto não pode ter mais que 50 caracteres.',
            'code.required' => 'O código do produto é obrigatório.',
            'code.string' => 'O código do produto deve ser um texto.',
            'code.unique' => 'O código do produto já está em uso.',
            'description.string' => 'A descrição do produto deve ser um texto.',
            'description.max' => 'A descrição do produto não pode ter mais que 500 caracteres.',
            'category_id.required' => 'A categoria do produto é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'fornecedor_id.required' => 'O fornecedor do produto é obrigatório.',
            'fornecedor_id.exists' => 'O fornecedor selecionado não existe.',
            'cost_price.required' => 'O preço de custo do produto é obrigatório.',
            'cost_price.numeric' => 'O preço de custo deve ser um número.',
            'sale_price.required' => 'O preço de venda do produto é obrigatório.',
            'sale_price.numeric' => 'O preço de venda deve ser um número.',
            'min_stock.required' => 'O estoque mínimo é obrigatório.',
            'min_stock.numeric' => 'O estoque mínimo deve ser um número.',
            'stock.required' => 'O estoque atual é obrigatório.',
            'stock.numeric' => 'O estoque atual deve ser um número.',
            'expiry_date.required' => 'A data de validade é obrigatória.',
            'expiry_date.date' => 'A data de validade deve ser uma data válida.',
        ];
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
