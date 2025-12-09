<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirecionamentoComercial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'direcionamentos_comerciais';

    protected $fillable = [
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com produtos
     */
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'direcionamento_comercial_id');
    }
}
