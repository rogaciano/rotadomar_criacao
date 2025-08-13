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

    /**
     * Calcula a necessidade total desta cor baseada nos produtos que usam o tecido
     * Necessidade = Quantidade da cor no produto × Consumo do tecido
     * 
     * @return float
     */
    public function getNecessidadeAttribute()
    {
        $necessidade = 0;
        
        // Buscar todos os produtos que usam este tecido
        $produtos = $this->tecido->produtos;
        
        foreach ($produtos as $produto) {
            // Buscar se o produto tem esta cor específica
            $produtoCor = $produto->cores()
                ->where('cor', $this->cor)
                ->first();
            
            if ($produtoCor) {
                // Necessidade = Quantidade da cor no produto × Consumo do tecido
                $necessidade += $produtoCor->quantidade * $produto->pivot->consumo;
            }
        }
        
        return $necessidade;
    }

    /**
     * Calcula o saldo (estoque - necessidade)
     * 
     * @return float
     */
    public function getSaldoAttribute()
    {
        return $this->quantidade - $this->necessidade;
    }
}
