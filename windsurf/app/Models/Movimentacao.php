<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimentacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimentacoes';

    protected $fillable = [
        'referencia',
        'comprometido',
        'localizacao_id',
        'data_entrada',
        'data_saida',
        'tipo_id',
        'situacao_id',
        'observacao'
    ];

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida' => 'date',
        'comprometido' => 'integer'
    ];

    // Relacionamentos
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'referencia', 'referencia');
    }

    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function situacao()
    {
        return $this->belongsTo(Situacao::class);
    }
}
