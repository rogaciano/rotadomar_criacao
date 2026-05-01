<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canAction(
            $this->isMethod('post') ? 'create' : 'update',
            'criacao'
        );
    }

    public function rules(): array
    {
        $produtoId = $this->route('produto')?->id ?? $this->route('produto');

        return [
            'referencia' => [
                Rule::requiredIf(!$this->isMethod('post')),
                'nullable',
                'string',
                'size:6',
                'regex:/^[A-Z]{6}$/',
                'unique:produtos,referencia,' . $produtoId,
            ],
            'descricao' => ['required', 'string', 'max:255'],
            'data_cadastro' => ['nullable', 'date'],
            'data_entrada_processo' => ['nullable', 'date'],
            'marca_id' => ['required', 'exists:marcas,id'],
            'estilista_id' => ['required', 'exists:estilistas,id'],
            'grupo_id' => ['required', 'exists:grupos,id'],
            'status_id' => ['required', 'exists:status,id'],
            'direcionamento_comercial_id' => ['nullable', 'exists:direcionamentos_comerciais,id'],
            'etapa_producao_id' => ['nullable', 'exists:etapas_producao,id'],
            'data_prevista_producao' => ['nullable', 'date'],
            'quantidade' => ['required', 'integer', 'min:0'],
            'media_mensal' => ['nullable', 'integer', 'min:0'],
            'variantes_cores' => ['nullable', 'integer', 'min:0'],
            'prioridade' => ['nullable', Rule::in(['Baixa', 'Média', 'Alta'])],
            'links_produto' => ['nullable', 'array'],
            'links_produto.*' => ['nullable', 'url', 'max:2048'],
            'faccao_localizacao_id' => ['nullable', 'exists:localizacoes,id'],
            'data_entrega' => ['nullable', 'date'],
            'mes_criacao' => ['nullable', 'date'],
            'mes_producao' => ['nullable', 'date'],
            'mes_lancamento' => ['nullable', 'date'],
            'preco_atacado' => ['nullable', 'numeric', 'min:0'],
            'preco_varejo' => ['nullable', 'numeric', 'min:0'],
            'obs_designer' => ['nullable', 'string'],
            'observacoes_criacao' => ['nullable', 'string'],
            'observacoes_adicionais' => ['nullable', 'string'],
            'foto_principal_criacao' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'imagens_criacao' => ['nullable', 'array'],
            'imagens_criacao.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'remover_foto_principal_criacao' => ['nullable', 'boolean'],
            'remover_anexos_criacao' => ['nullable', 'array'],
            'remover_anexos_criacao.*' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $links = array_values(array_filter(
            array_map(
                static fn ($link) => is_string($link) ? trim($link) : $link,
                $this->input('links_produto', [])
            ),
            static fn ($link) => filled($link)
        ));

        $this->merge([
            'links_produto' => $links,
            'mes_criacao' => $this->normalizeMonthInput($this->input('mes_criacao')),
            'mes_producao' => $this->normalizeMonthInput($this->input('mes_producao')),
            'mes_lancamento' => $this->normalizeMonthInput($this->input('mes_lancamento')),
        ]);
    }

    private function normalizeMonthInput(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (preg_match('/^\d{4}-\d{2}$/', $value) === 1) {
            return $value . '-01';
        }

        return $value;
    }
}
