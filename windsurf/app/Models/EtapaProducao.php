<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtapaProducao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'etapas_producao';

    const SLUG_AGUARDANDO_RETIRADA = 'aguardando_retirada';
    const SLUG_AGUARDANDO_MOTORISTA = 'aguardando_motorista';
    const SLUG_EM_TRANSITO = 'em_transito';
    const SLUG_COLETADO = 'coletado';
    const SLUG_AGENDAMENTO = 'agendamento';
    const SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA = 'saida_fabrica_solicitar_retirada';
    const SLUG_RETIRADA_CONFIRMADA_FACCAO = 'retirada_confirmada_faccao';
    const SLUG_ENTREGA_CONFIRMADA_FABRICA = 'entrega_confirmada_fabrica';
    const SLUG_CHECK_IN = 'check_in';
    const SLUG_CHEGADA_PRODUTO_FABRICA = 'chegada_produto_fabrica';

    /** Etapas do fluxo na facção / planejamento (produto_localizacao). */
    const CONTEXTO_LOCALIZACAO = 'localizacao';

    /** Etapas do fluxo logístico (coleta, trânsito, entrega). */
    const CONTEXTO_LOGISTICA = 'logistica';

    protected $fillable = [
        'nome',
        'slug',
        'contexto',
        'inicia_logistica',
        'descricao',
        'cor',
        'icone',
        'ativo',
        'localizacao_id',
        'ordem',
        'obriga_data_entrega_faccao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'localizacao_id' => 'integer',
        'ordem' => 'integer',
        'obriga_data_entrega_faccao' => 'boolean',
        'inicia_logistica' => 'boolean',
    ];

    /**
     * Setor (Localização) que deve ser notificado nesta etapa
     */
    public function setor()
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_id');
    }

    /**
     * Transições que partem desta etapa (próximas etapas possíveis)
     */
    public function transicoesOrigem()
    {
        return $this->hasMany(EtapaTransicao::class, 'etapa_origem_id');
    }

    /**
     * Transições que chegam a esta etapa
     */
    public function transicoesDestino()
    {
        return $this->hasMany(EtapaTransicao::class, 'etapa_destino_id');
    }

    /**
     * Próximas etapas possíveis (através das transições ativas)
     */
    public function proximasEtapas()
    {
        return $this->belongsToMany(
            EtapaProducao::class,
            'etapas_transicoes',
            'etapa_origem_id',
            'etapa_destino_id'
        )->withPivot(['label_botao', 'cor_botao', 'ativo', 'ordem'])
         ->wherePivot('ativo', true)
         ->orderByPivot('ordem');
    }

    /**
     * Etapas anteriores (de onde posso voltar)
     */
    public function etapasAnteriores()
    {
        return $this->belongsToMany(
            EtapaProducao::class,
            'etapas_transicoes',
            'etapa_destino_id',
            'etapa_origem_id'
        )->withPivot(['label_botao', 'cor_botao', 'ativo', 'ordem'])
         ->wherePivot('ativo', true);
    }

    /**
     * Scope para etapas ativas ordenadas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true)->orderBy('ordem');
    }

    public function scopeParaLocalizacao($query)
    {
        return $query->where('contexto', self::CONTEXTO_LOCALIZACAO);
    }

    public function scopeParaLogistica($query)
    {
        return $query->where('contexto', self::CONTEXTO_LOGISTICA);
    }

    public function scopeIniciaLogistica($query)
    {
        return $query->where('inicia_logistica', true);
    }

    public static function contextosDisponiveis(): array
    {
        return [
            self::CONTEXTO_LOCALIZACAO => 'Produção — facção / planejamento',
            self::CONTEXTO_LOGISTICA => 'Logística — retirada, trânsito e entrega',
        ];
    }

    public function isLocalizacao(): bool
    {
        return $this->contexto === self::CONTEXTO_LOCALIZACAO;
    }

    /**
     * Transição permitida entre etapas (mesmo contexto ou handoff produção → logística).
     */
    public function podeTransicionarPara(self $destino): bool
    {
        if ($this->id === $destino->id) {
            return false;
        }

        if ($this->contexto === $destino->contexto) {
            return true;
        }

        return $this->isLocalizacao()
            && $destino->isLogistica()
            && $destino->inicia_logistica;
    }

    /**
     * Obter transições ativas para botões na UI
     */
    public function getTransicoesParaBotoes()
    {
        return $this->transicoesOrigem()
            ->where('ativo', true)
            ->with('etapaDestino')
            ->orderBy('ordem')
            ->get();
    }

    /**
     * Cores disponíveis para etapas
     */
    /**
     * Buscar etapa por slug imutável
     */
    public static function porSlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Etapa do fluxo logístico (coleta / trânsito / entrega).
     */
    public function isLogistica(): bool
    {
        return $this->contexto === self::CONTEXTO_LOGISTICA;
    }

    /**
     * Etapa que encerra a produção na facção e inicia o processo logístico.
     */
    public function encerraProducaoIniciaLogistica(): bool
    {
        return (bool) $this->inicia_logistica;
    }

    public static function etapaInicioLogistica(): ?self
    {
        return static::query()
            ->where('ativo', true)
            ->where('inicia_logistica', true)
            ->first();
    }

    public static function slugsLogisticaPadrao(): array
    {
        return [
            self::SLUG_AGENDAMENTO,
            self::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA,
            self::SLUG_RETIRADA_CONFIRMADA_FACCAO,
            self::SLUG_EM_TRANSITO,
            self::SLUG_ENTREGA_CONFIRMADA_FABRICA,
            self::SLUG_CHECK_IN,
            self::SLUG_CHEGADA_PRODUTO_FABRICA,
        ];
    }

    public static function etapasLogisticaPadrao(): array
    {
        return [
            self::SLUG_AGENDAMENTO => 'Agendamento',
            self::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA => 'Saída da Fábrica / Solicitar Retirada',
            self::SLUG_RETIRADA_CONFIRMADA_FACCAO => 'Retirada Confirmada pela Facção',
            self::SLUG_EM_TRANSITO => 'Em Trânsito',
            self::SLUG_ENTREGA_CONFIRMADA_FABRICA => 'Entrega Confirmada na Fábrica',
            self::SLUG_CHECK_IN => 'Check-in',
            self::SLUG_CHEGADA_PRODUTO_FABRICA => 'Chegada do Produto na Fábrica',
        ];
    }

    public static function etapaLogisticaPorSlug(string $slug): ?self
    {
        return static::query()
            ->where('contexto', self::CONTEXTO_LOGISTICA)
            ->where('slug', $slug)
            ->first();
    }

    public static function coresDisponiveis(): array
    {
        return [
            'blue' => 'Azul',
            'green' => 'Verde',
            'yellow' => 'Amarelo',
            'red' => 'Vermelho',
            'purple' => 'Roxo',
            'gray' => 'Cinza',
            'indigo' => 'Índigo',
            'pink' => 'Rosa',
            'orange' => 'Laranja'
        ];
    }
}
