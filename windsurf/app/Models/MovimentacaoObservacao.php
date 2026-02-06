<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoObservacao extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes_observacoes';

    protected $fillable = [
        'movimentacao_id',
        'observacao',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Movimentacao
     */
    public function movimentacao()
    {
        return $this->belongsTo(Movimentacao::class);
    }
}
