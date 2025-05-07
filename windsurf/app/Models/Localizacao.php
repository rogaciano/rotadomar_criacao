<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localizacao extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'localizacoes';

    protected $fillable = [
        'nome_localizacao',
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
