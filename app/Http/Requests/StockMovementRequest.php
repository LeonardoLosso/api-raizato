<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:entry,exit',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ];
    }
}
