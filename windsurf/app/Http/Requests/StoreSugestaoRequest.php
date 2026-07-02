<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSugestaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'assunto' => 'required|string|max:255',
            'texto' => 'required|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'assunto.required' => 'O assunto é obrigatório.',
            'assunto.max' => 'O assunto não pode ter mais de 255 caracteres.',
            'texto.required' => 'O texto da sugestão é obrigatório.',
            'texto.max' => 'O texto não pode ter mais de 5000 caracteres.',
        ];
    }
}
