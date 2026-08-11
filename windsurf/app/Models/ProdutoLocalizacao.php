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
        'data_entrega_faccao',
        'fluxo_logistica',
        'eh_origem_logistica',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'data_prevista_faccao' => 'date',
        'data_envio_faccao' => 'date',
        'data_retorno_faccao' => 'date',
        'concluido' => 'integer',
        'etapa_atual_id' => 'integer',
        'etapa_anterior_id' => 'integer',
        'data_entrega_faccao' => 'date',
        'eh_origem_logistica' => 'boolean',
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
     * IDs de localizações que já possuem linha em etapa logística (ida/volta em andamento).
     */
    public static function idsLocalizacoesEmFluxoLogistica(int $produtoId): \Illuminate\Support\Collection
    {
        return static::query()
            ->where('produto_id', $produtoId)
            ->whereHas('etapaAtual', function ($q) {
                $q->where('contexto', EtapaProducao::CONTEXTO_LOGISTICA);
            })
            ->pluck('localizacao_id')
            ->unique()
            ->filter()
            ->values();
    }

    /**
     * Localizações planejadas na ficha que ainda podem ser liberadas para a ida.
     */
    public static function localizacoesOrigemIdaDisponiveis(int $produtoId): \Illuminate\Support\Collection
    {
        $destinosPlanejados = static::query()
            ->where('produto_id', $produtoId)
            ->where('eh_origem_logistica', false)
            ->pluck('localizacao_id')
            ->unique()
            ->filter()
            ->values();

        return Localizacao::query()
            ->where('ativo', true)
            ->whereNotIn('id', $destinosPlanejados)
            ->orderBy('nome_localizacao')
            ->get();
    }

    /**
     * Quantidades por localização ainda disponíveis para liberação (ida).
     */
    public static function quantidadesOrigemIdaDisponiveis(int $produtoId): \Illuminate\Support\Collection
    {
        $disponiveis = static::localizacoesOrigemIdaDisponiveis($produtoId)->pluck('id');

        if ($disponiveis->isEmpty()) {
            return collect();
        }

        return static::query()
            ->where('produto_id', $produtoId)
            ->where('eh_origem_logistica', true)
            ->whereIn('localizacao_id', $disponiveis)
            ->whereDoesntHave('etapaAtual', function ($q) {
                $q->where('contexto', EtapaProducao::CONTEXTO_LOGISTICA);
            })
            ->get()
            ->groupBy('localizacao_id')
            ->map(fn ($rows) => $rows->first()->quantidade);
    }

    /**
     * Verifica se a localização ainda pode ser liberada para a ida.
     */
    public static function localizacaoDisponivelParaLiberacaoIda(int $produtoId, int $localizacaoId): bool
    {
        if (!Localizacao::query()->whereKey($localizacaoId)->where('ativo', true)->exists()) {
            return false;
        }

        return !static::localizacaoPlanejadaNoProduto($produtoId, $localizacaoId)
            && !static::idsLocalizacoesEmFluxoLogistica($produtoId)->contains($localizacaoId);
    }

    /**
     * Linha de planejamento existente para reutilizar na liberação (evita duplicar).
     */
    public static function registroPlanejamentoParaLiberacaoIda(int $produtoId, int $localizacaoId): ?self
    {
        return static::query()
            ->where('produto_id', $produtoId)
            ->where('localizacao_id', $localizacaoId)
            ->where('eh_origem_logistica', true)
            ->whereDoesntHave('etapaAtual', function ($q) {
                $q->where('contexto', EtapaProducao::CONTEXTO_LOGISTICA);
            })
            ->first();
    }

    /**
     * Localizações já cadastradas na ficha do produto (planejamento).
     * Usado no modal "Liberar para Produção" para restringir a origem da ida.
     */
    public static function localizacoesPlanejadasParaProduto(int $produtoId): \Illuminate\Support\Collection
    {
        $ids = static::query()
            ->where('produto_id', $produtoId)
            ->where('eh_origem_logistica', false)
            ->pluck('localizacao_id')
            ->unique()
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        return Localizacao::query()
            ->whereIn('id', $ids)
            ->where('ativo', true)
            ->orderBy('nome_localizacao')
            ->get();
    }

    /**
     * Verifica se a localização informada está planejada na ficha do produto.
     */
    public static function localizacaoPlanejadaNoProduto(int $produtoId, int $localizacaoId): bool
    {
        return static::query()
            ->where('produto_id', $produtoId)
            ->where('localizacao_id', $localizacaoId)
            ->where('eh_origem_logistica', false)
            ->exists();
    }

    /**
     * Destinos permitidos no agendamento: outras localizações já planejadas
     * na ficha do produto (view), exceto a origem deste registro.
     *
     * @param  array<int>|null  $restringirIds  Interseção opcional (ex.: localizações permitidas ao usuário)
     */
    public function destinosLogisticaPermitidos(?array $restringirIds = null): \Illuminate\Support\Collection
    {
        $ids = static::query()
            ->where('produto_id', $this->produto_id)
            ->where('eh_origem_logistica', false)
            ->where('localizacao_id', '!=', $this->localizacao_id)
            ->pluck('localizacao_id')
            ->unique()
            ->values();

        if ($restringirIds !== null) {
            $ids = $ids->intersect($restringirIds)->values();
        }

        if ($ids->isEmpty()) {
            return collect();
        }

        return Localizacao::query()
            ->whereIn('id', $ids)
            ->where('ativo', true)
            ->orderBy('nome_localizacao')
            ->get();
    }

    /**
     * Verifica se o destino informado está entre as localizações planejadas do produto.
     */
    public function destinoPermitidoParaColeta(int $destinoLocalizacaoId, ?array $restringirIds = null): bool
    {
        $planejado = static::query()
            ->where('produto_id', $this->produto_id)
            ->where('localizacao_id', $destinoLocalizacaoId)
            ->where('localizacao_id', '!=', $this->localizacao_id)
            ->exists();

        if (!$planejado) {
            return false;
        }

        if ($restringirIds !== null && !in_array($destinoLocalizacaoId, $restringirIds, true)) {
            return false;
        }

        return true;
    }

    /**
     * Coleta logística ativa (agendado ou em_transito)
     */
    public function coletaLogisticaAtiva()
    {
        return $this->hasOne(ColetaLogistica::class, 'produto_localizacao_id')
            ->whereIn('status', [ColetaLogistica::STATUS_AGENDADO, ColetaLogistica::STATUS_EM_TRANSITO])
            ->latest();
    }

    /**
     * Todas as coletas logísticas (histórico)
     */
    public function coletasLogisticas()
    {
        return $this->hasMany(ColetaLogistica::class, 'produto_localizacao_id');
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
