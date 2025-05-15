<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'referencia',
        'descricao',
        'data_cadastro',
        'marca_id',
        'quantidade',
        'tecido_id',
        'estilista_id',
        'grupo_id',
        'preco_atacado',
        'preco_varejo',
        'status_id',
        'anexo_ficha_producao',
        'anexo_catalogo_vendas'
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'preco_atacado' => 'decimal:2',
        'preco_varejo' => 'decimal:2',
        'quantidade' => 'integer'
    ];

    // Relacionamentos
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function tecido()
    {
        return $this->belongsTo(Tecido::class);
    }

    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    public function grupoProduto()
    {
        return $this->belongsTo(GrupoProduto::class, 'grupo_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class, 'produto_id');
    }
}
