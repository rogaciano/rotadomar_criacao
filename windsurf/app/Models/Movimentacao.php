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
        'comprometido',
        'produto_id',
        'localizacao_id',
        'data_entrada',
        'data_saida',
        'data_devolucao',
        'tipo_id',
        'situacao_id',
        'observacao'
    ];

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida' => 'date',
        'data_devolucao' => 'date',
        'comprometido' => 'integer'
    ];

    // Definindo relacionamentos para serem carregados automaticamente
    protected $with = ['produto', 'localizacao', 'tipo', 'situacao'];

    // Relacionamentos
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
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
