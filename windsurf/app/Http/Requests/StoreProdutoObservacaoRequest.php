<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoObservacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canCreate('produto_observacoes') ?? false;
    }

    public function rules(): array
    {
        return [
            'produto_id' => 'required|exists:produtos,id',
            'observacao' => 'required|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'produto_id.required' => 'O produto é obrigatório.',
            'produto_id.exists' => 'O produto selecionado não existe.',
            'observacao.required' => 'A observação é obrigatória.',
            'observacao.max' => 'A observação não pode ter mais de 5000 caracteres.',
        ];
    }
}
