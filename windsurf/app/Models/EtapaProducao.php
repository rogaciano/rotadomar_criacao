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

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'cor',
        'icone',
        'ativo',
        'localizacao_id',
        'ordem',
        'obriga_data_entrega_faccao'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'localizacao_id' => 'integer',
        'ordem' => 'integer',
        'obriga_data_entrega_faccao' => 'boolean'
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
     * Verifica se esta etapa faz parte do fluxo logístico (possui slug protegido)
     */
    public function isLogistica(): bool
    {
        return !empty($this->slug);
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
