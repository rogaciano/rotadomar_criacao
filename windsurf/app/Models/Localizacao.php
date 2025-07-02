<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Localizacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'localizacoes';

    protected $fillable = [
        'nome_localizacao',
        'ativo',
        'prazo',
        'observacoes'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'observacoes' => 'string'
    ];

    // Relacionamento com movimentacoes
    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }

    // Relacionamento com usuários
    public function usuarios()
    {
        return $this->hasMany(User::class, 'localizacao_id');
    }
}
