<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    protected $fillable = [
        'movimentacao_id',
        'localizacao_id',
        'tipo',
        'titulo',
        'mensagem',
        'link',
        'visualizada_por',
        'visualizada_em'
    ];

    protected $casts = [
        'visualizada_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacionamentos
    public function movimentacao(): BelongsTo
    {
        return $this->belongsTo(Movimentacao::class);
    }

    public function localizacao(): BelongsTo
    {
        return $this->belongsTo(Localizacao::class);
    }

    public function visualizadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visualizada_por');
    }

    // Scopes
    public function scopeNaoVisualizadas($query)
    {
        return $query->whereNull('visualizada_por');
    }

    public function scopePorLocalizacao($query, $localizacaoId)
    {
        return $query->where('localizacao_id', $localizacaoId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Métodos auxiliares
    public function isVisualizada(): bool
    {
        return !is_null($this->visualizada_por);
    }

    public function marcarComoVisualizada(User $user): void
    {
        $this->update([
            'visualizada_por' => $user->id,
            'visualizada_em' => now()
        ]);
    }
}
