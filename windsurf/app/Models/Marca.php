<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Produto;
use App\Services\MarcaAnalyticsService;

class Marca extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome_marca',
        'logo_path',
        'cor_fundo',
        'cor_fonte',
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

    /**
     * Get total number of products for this brand
     */
    public function getTotalProdutosAttribute()
    {
        return $this->produtos()->count();
    }

    /**
     * Get product count by estilista
     */
    public function getProdutosPorEstilista()
    {
        return app(MarcaAnalyticsService::class)->produtosPorEstilista($this);
    }

    /**
     * Get product count by localizacao (from last movimentacao)
     */
    public function getProdutosPorLocalizacao()
    {
        return app(MarcaAnalyticsService::class)->produtosPorLocalizacao($this);
    }

    /**
     * Get product count by grupo
     */
    public function getProdutosPorGrupo()
    {
        return app(MarcaAnalyticsService::class)->produtosPorGrupo($this);
    }

    /**
     * Get product count by status
     */
    public function getProdutosPorStatus()
    {
        return app(MarcaAnalyticsService::class)->produtosPorStatus($this);
    }
}
