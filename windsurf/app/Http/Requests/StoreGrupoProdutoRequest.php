<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrupoProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'descricao' => 'required|string|max:255',
            'ativo' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição não pode ter mais de 255 caracteres.',
            'ativo.required' => 'O status ativo é obrigatório.',
        ];
    }
}
