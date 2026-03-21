<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefinirEtapaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'etapa_id' => 'required|exists:etapas_producao,id',
            'observacao' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'etapa_id.required' => 'A etapa é obrigatória.',
            'etapa_id.exists' => 'A etapa selecionada não existe.',
            'observacao.max' => 'A observação não pode ter mais de 255 caracteres.',
        ];
    }
}
