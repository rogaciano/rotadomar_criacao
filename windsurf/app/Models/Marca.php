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
        'logo_path',
        'ativo',
        'suporte_marca'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacionamento com produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
