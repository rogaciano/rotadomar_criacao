<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovimentacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canUpdate('movimentacoes') ?? false;
    }

    public function rules(): array
    {
        return [
            'produto_id' => 'required|exists:produtos,id',
            'tipo_id' => 'required|exists:tipos,id',
            'situacao_id' => 'required|exists:situacoes,id',
            'localizacao_id' => 'required|exists:localizacoes,id',
            'data_entrada' => 'required|date',
            'data_saida' => 'nullable|date|after_or_equal:data_entrada',
            'data_devolucao' => 'nullable|date|after_or_equal:data_entrada',
            'observacao' => 'nullable|string',
            'anexo' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'produto_id.required' => 'O campo Produto é obrigatório.',
            'produto_id.exists' => 'O produto selecionado não existe.',
            'tipo_id.required' => 'O campo Tipo de Movimentação é obrigatório.',
            'tipo_id.exists' => 'O tipo de movimentação selecionado não existe.',
            'situacao_id.required' => 'O campo Situação é obrigatório.',
            'situacao_id.exists' => 'A situação selecionada não existe.',
            'localizacao_id.required' => 'O campo Localização é obrigatório.',
            'localizacao_id.exists' => 'A localização selecionada não existe.',
            'data_entrada.required' => 'O campo Data de Entrada é obrigatório.',
            'data_entrada.date' => 'O campo Data de Entrada deve ser uma data válida.',
            'data_saida.date' => 'O campo Data de Saída deve ser uma data válida.',
            'data_saida.after_or_equal' => 'A Data de Saída deve ser igual ou posterior à Data de Entrada.',
            'data_devolucao.date' => 'O campo Data de Devolução deve ser uma data válida.',
            'data_devolucao.after_or_equal' => 'A Data de Devolução deve ser igual ou posterior à Data de Entrada.',
            'anexo.file' => 'O anexo deve ser um arquivo válido.',
            'anexo.mimes' => 'O anexo deve ser uma imagem (jpg, jpeg ou png).',
            'anexo.max' => 'O anexo não pode ser maior que 10MB.',
        ];
    }
}
