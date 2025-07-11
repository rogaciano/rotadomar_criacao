<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TecidoCorEstoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'tecido_id',
        'cor',
        'codigo_cor',
        'quantidade',
        'quantidade_pretendida',
        'data_atualizacao',
        'observacoes'
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'quantidade_pretendida' => 'decimal:2',
        'data_atualizacao' => 'date'
    ];

    /**
     * Relacionamento com o tecido
     */
    public function tecido()
    {
        return $this->belongsTo(Tecido::class);
    }
}
