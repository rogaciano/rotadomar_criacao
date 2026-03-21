<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoLocalizacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canCreate('produto_localizacao') ?? false;
    }

    public function rules(): array
    {
        return [
            'localizacao_id' => 'required|exists:localizacoes,id',
            'quantidade' => 'required|integer|min:1',
            'data_prevista_faccao' => 'nullable|date',
            'data_envio_faccao' => 'nullable|date',
            'data_retorno_faccao' => 'nullable|date|required_if:concluido,1',
            'ordem_producao' => 'required|string|max:30',
            'observacao' => 'nullable|string|max:255',
            'concluido' => 'nullable|boolean',
            'data_entrega_faccao' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'localizacao_id.required' => 'A localização é obrigatória.',
            'localizacao_id.exists' => 'A localização selecionada não existe.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade deve ser pelo menos 1.',
            'ordem_producao.required' => 'A ordem de produção é obrigatória.',
            'ordem_producao.max' => 'A ordem de produção não pode ter mais de 30 caracteres.',
            'data_retorno_faccao.required_if' => 'A data de retorno é obrigatória quando marcado como concluído.',
        ];
    }
}
