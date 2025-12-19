<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaTransicao extends Model
{
    use HasFactory;

    protected $table = 'etapas_transicoes';

    protected $fillable = [
        'etapa_origem_id',
        'etapa_destino_id',
        'label_botao',
        'cor_botao',
        'ativo',
        'ordem'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ordem' => 'integer'
    ];

    /**
     * Etapa de onde parte a transição
     */
    public function etapaOrigem()
    {
        return $this->belongsTo(EtapaProducao::class, 'etapa_origem_id');
    }

    /**
     * Etapa para onde vai a transição
     */
    public function etapaDestino()
    {
        return $this->belongsTo(EtapaProducao::class, 'etapa_destino_id');
    }

    /**
     * Obter o texto do botão (usa label_botao ou nome da etapa destino)
     */
    public function getTextoBotaoAttribute(): string
    {
        return $this->label_botao ?: ($this->etapaDestino->nome ?? 'Avançar');
    }

    /**
     * Scope para transições ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true)->orderBy('ordem');
    }
}
