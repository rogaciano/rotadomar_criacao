<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoLocalizacaoHistoricoEtapa extends Model
{
    use HasFactory;

    protected $table = 'produto_localizacao_historico_etapas';

    protected $fillable = [
        'produto_localizacao_id',
        'etapa_anterior_id',
        'etapa_nova_id',
        'user_id',
        'updated_by_user_id',
        'acao',
        'observacao'
    ];

    /**
     * Relacionamento com ProdutoLocalizacao
     */
    public function produtoLocalizacao()
    {
        return $this->belongsTo(ProdutoLocalizacao::class, 'produto_localizacao_id');
    }

    /**
     * Etapa anterior
     */
    public function etapaAnterior()
    {
        return $this->belongsTo(EtapaProducao::class, 'etapa_anterior_id');
    }

    /**
     * Nova etapa
     */
    public function etapaNova()
    {
        return $this->belongsTo(EtapaProducao::class, 'etapa_nova_id');
    }

    /**
     * Usuário que fez a alteração
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Usuário que atualizou o registro
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    /**
     * Descrição da ação para exibição
     */
    public function getDescricaoAcaoAttribute(): string
    {
        return match($this->acao) {
            'avancar' => 'Avançou etapa',
            'voltar' => 'Voltou etapa',
            'definir_inicial' => 'Definiu etapa inicial',
            default => $this->acao
        };
    }

    /**
     * Scope para histórico de um produto_localizacao ordenado por data
     */
    public function scopePorProdutoLocalizacao($query, $produtoLocalizacaoId)
    {
        return $query->where('produto_localizacao_id', $produtoLocalizacaoId)
            ->orderBy('created_at', 'desc');
    }
}
