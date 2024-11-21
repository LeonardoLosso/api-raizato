<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CategoryRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ];
    }
}
