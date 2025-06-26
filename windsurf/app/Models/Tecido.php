<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tecido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'descricao',
        'referencia',
        'data_cadastro',
        'ativo',
        'ultima_consulta_estoque',
        'quantidade_estoque'
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'ultima_consulta_estoque' => 'datetime',
        'quantidade_estoque' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    // Relacionamento com produtos
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_tecido')
                    ->withPivot('consumo')
                    ->withTimestamps();
    }
}
