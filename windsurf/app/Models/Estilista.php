<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Produto;
use App\Models\Marca;

class Estilista extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_estilista',
        'ativo',
        'marca_id',
        'suporte_marca',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Obter os produtos deste estilista.
     */
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    /**
     * Obter a marca associada a este estilista.
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
