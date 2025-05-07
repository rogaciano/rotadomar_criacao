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
        'ativo'
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
