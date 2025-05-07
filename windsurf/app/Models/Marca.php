<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Produto;

class Marca extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome_marca',
        'ativo',
        'data_cadastro',
        'suporte_marca'
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'ativo' => 'boolean'
    ];

    // Relacionamento com produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
