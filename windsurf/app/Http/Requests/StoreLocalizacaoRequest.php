<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocalizacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nome_localizacao' => 'required|string|max:255',
            'prazo' => 'nullable|integer|min:0',
            'capacidade' => 'nullable|integer|min:0',
            'ativo' => 'boolean',
            'faz_movimentacao' => 'boolean',
            'pode_ver_todas_notificacoes' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome_localizacao.required' => 'O nome da localização é obrigatório.',
            'nome_localizacao.max' => 'O nome não pode ter mais de 255 caracteres.',
            'prazo.integer' => 'O prazo deve ser um número inteiro.',
            'prazo.min' => 'O prazo não pode ser negativo.',
            'capacidade.integer' => 'A capacidade deve ser um número inteiro.',
            'capacidade.min' => 'A capacidade não pode ser negativa.',
        ];
    }
}
