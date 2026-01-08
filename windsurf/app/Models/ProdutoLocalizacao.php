<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProdutoLocalizacao extends Pivot
{
    use HasFactory;

    protected $table = 'produto_localizacao';

    // Habilitar auto-incremento para ter acesso ao ID do pivot
    public $incrementing = true;

    protected $fillable = [
        'produto_id',
        'localizacao_id',
        'quantidade',
        'data_prevista_faccao',
        'data_envio_faccao',
        'data_retorno_faccao',
        'ordem_producao',
        'observacao',
        'concluido',
        'etapa_atual_id',
        'etapa_anterior_id',
        'data_entrega_faccao'
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'data_prevista_faccao' => 'date',
        'data_envio_faccao' => 'date',
        'data_retorno_faccao' => 'date',
        'concluido' => 'integer',
        'etapa_atual_id' => 'integer',
        'etapa_anterior_id' => 'integer',
        'data_entrega_faccao' => 'date'
    ];

    /**
     * Relacionamento com o produto
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Relacionamento com a localização
     */
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }

    /**
     * Etapa atual de produção
     */
    public function etapaAtual()
    {
        return $this->belongsTo(EtapaProducao::class, 'etapa_atual_id');
    }

    /**
     * Etapa anterior de produção
     */
    public function etapaAnterior()
    {
        return $this->belongsTo(EtapaProducao::class, 'etapa_anterior_id');
    }

    /**
     * Histórico de mudanças de etapa
     */
    public function historicoEtapas()
    {
        return $this->hasMany(ProdutoLocalizacaoHistoricoEtapa::class, 'produto_localizacao_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Avançar para uma nova etapa
     */
    public function avancarEtapa(int $novaEtapaId, int $userId, ?string $observacao = null): bool
    {
        $etapaAnteriorId = $this->etapa_atual_id;

        $this->update([
            'etapa_anterior_id' => $etapaAnteriorId,
            'etapa_atual_id' => $novaEtapaId
        ]);

        // Registrar no histórico
        ProdutoLocalizacaoHistoricoEtapa::create([
            'produto_localizacao_id' => $this->id,
            'etapa_anterior_id' => $etapaAnteriorId,
            'etapa_nova_id' => $novaEtapaId,
            'user_id' => $userId,
            'acao' => 'avancar',
            'observacao' => $observacao
        ]);

        return true;
    }

    /**
     * Voltar para a etapa anterior
     */
    public function voltarEtapa(int $userId, ?string $observacao = null): bool
    {
        if (!$this->etapa_anterior_id) {
            return false;
        }

        $etapaAtualId = $this->etapa_atual_id;
        $etapaAnteriorId = $this->etapa_anterior_id;

        // Buscar a etapa anterior da anterior (se existir no histórico)
        $historicoAnterior = $this->historicoEtapas()
            ->where('etapa_nova_id', $etapaAnteriorId)
            ->first();

        $novaEtapaAnteriorId = $historicoAnterior?->etapa_anterior_id;

        $this->update([
            'etapa_atual_id' => $etapaAnteriorId,
            'etapa_anterior_id' => $novaEtapaAnteriorId
        ]);

        // Registrar no histórico
        ProdutoLocalizacaoHistoricoEtapa::create([
            'produto_localizacao_id' => $this->id,
            'etapa_anterior_id' => $etapaAtualId,
            'etapa_nova_id' => $etapaAnteriorId,
            'user_id' => $userId,
            'acao' => 'voltar',
            'observacao' => $observacao
        ]);

        return true;
    }

    /**
     * Definir etapa inicial
     */
    public function definirEtapaInicial(int $etapaId, int $userId, ?string $observacao = null): bool
    {
        $this->update([
            'etapa_atual_id' => $etapaId,
            'etapa_anterior_id' => null
        ]);

        // Registrar no histórico
        ProdutoLocalizacaoHistoricoEtapa::create([
            'produto_localizacao_id' => $this->id,
            'etapa_anterior_id' => null,
            'etapa_nova_id' => $etapaId,
            'user_id' => $userId,
            'acao' => 'definir_inicial',
            'observacao' => $observacao
        ]);

        return true;
    }

    /**
     * Obter a URL da ordem de produção
     */
    public function getOrdemProducaoUrlAttribute(): ?string
    {
        if (!$this->ordem_producao) {
            return null;
        }

        return "https://dapic.app/admin/ordemproducao#codigo/{$this->ordem_producao}";
    }

    /**
     * Obter próximas etapas possíveis
     */
    public function getProximasEtapasPossiveis()
    {
        if (!$this->etapa_atual_id) {
            return collect();
        }

        return $this->etapaAtual?->getTransicoesParaBotoes() ?? collect();
    }
}
