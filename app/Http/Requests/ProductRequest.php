<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (in_array($this->method(), ['POST', 'PUT', 'PATCH']))
            return Gate::allows('userDeny', auth()->user());

        return true;
    }

    public function rules(): array
    {
        if (in_array($this->method(), ['POST', 'PUT', 'PATCH']))
            return [
                'name' => 'required|string|max:50',
                'code' => 'required|string|unique:products,code',
                'description' => 'nullable|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'fornecedor_id' => 'required|exists:fornecedores,id',
                'cost_price' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'min_stock' => 'required|integer',
                'expiry_date' => 'required|date',
            ];
    }
}
