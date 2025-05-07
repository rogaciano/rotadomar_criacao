<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Situacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'situacoes';

    protected $fillable = [
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Relacionamento com movimentacoes
    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
