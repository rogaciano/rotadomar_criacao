<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEtapaProducaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:255',
            'cor' => 'required|string|max:20',
            'icone' => 'nullable|string|max:50',
            'localizacao_id' => 'nullable|exists:localizacoes,id',
            'ativo' => 'boolean',
            'ordem' => 'required|integer|min:0',
            'obriga_data_entrega_faccao' => 'boolean',
            'transicoes' => 'nullable|array',
            'transicoes.*.etapa_destino_id' => 'nullable|exists:etapas_producao,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da etapa é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 100 caracteres.',
            'cor.required' => 'A cor é obrigatória.',
            'ordem.required' => 'A ordem é obrigatória.',
            'ordem.integer' => 'A ordem deve ser um número inteiro.',
        ];
    }
}
