<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendarColetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'produto_localizacao_id' => 'required|integer|exists:produto_localizacao,id',
            'motorista_user_id' => 'required|integer|exists:users,id',
            'veiculo_id' => 'required|integer|exists:veiculos,id',
            'destino_localizacao_id' => 'required|integer|exists:localizacoes,id',
            'inicio_previsto_em' => 'required|date|after_or_equal:today',
            'retorno_previsto_em' => 'required|date|after:inicio_previsto_em',
            'observacao_motorista' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'produto_localizacao_id.required' => 'O produto/localização é obrigatório.',
            'produto_localizacao_id.exists' => 'O produto/localização selecionado não existe.',
            'motorista_user_id.required' => 'O usuário responsável pela coleta é obrigatório.',
            'motorista_user_id.exists' => 'O usuário responsável selecionado não existe.',
            'veiculo_id.required' => 'O veículo é obrigatório.',
            'veiculo_id.exists' => 'O veículo selecionado não existe.',
            'destino_localizacao_id.required' => 'O destino é obrigatório.',
            'destino_localizacao_id.exists' => 'O destino selecionado não existe.',
            'inicio_previsto_em.required' => 'A data/hora de início é obrigatória.',
            'inicio_previsto_em.after_or_equal' => 'A data de início deve ser hoje ou posterior.',
            'retorno_previsto_em.required' => 'A data/hora de retorno é obrigatória.',
            'retorno_previsto_em.after' => 'A data de retorno deve ser posterior à de início.',
            'observacao_motorista.max' => 'A observação não pode ter mais de 1000 caracteres.',
        ];
    }
}
