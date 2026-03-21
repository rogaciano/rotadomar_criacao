<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da permissão é obrigatório.',
            'display_name.required' => 'O nome de exibição é obrigatório.',
        ];
    }
}
