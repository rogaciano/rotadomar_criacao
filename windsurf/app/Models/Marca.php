<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
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

    protected $appends = [
        'logo_url',
    ];

    // Relacionamento com produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        $resolvedPath = $this->getResolvedLogoPath();

        return $resolvedPath ? asset('storage/' . $resolvedPath) : null;
    }

    public function getResolvedLogoPath(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        if (Storage::disk('public')->exists($this->logo_path)) {
            return $this->logo_path;
        }

        $directory = 'logos';
        $filename = pathinfo($this->logo_path, PATHINFO_FILENAME);
        $normalizedFilename = strtolower(preg_replace('/\.(jpe?g|png|gif|webp)$/i', '', $filename));
        $normalizedFilename = preg_replace('/^\d+_/', '', $normalizedFilename);
        $normalizedFilename = str_replace(['-', '_', ' '], '', $normalizedFilename);

        foreach (Storage::disk('public')->files($directory) as $file) {
            $candidate = pathinfo($file, PATHINFO_FILENAME);
            $normalizedCandidate = strtolower(preg_replace('/\.(jpe?g|png|gif|webp)$/i', '', $candidate));
            $normalizedCandidate = preg_replace('/^\d+_/', '', $normalizedCandidate);
            $normalizedCandidate = str_replace(['-', '_', ' '], '', $normalizedCandidate);

            if ($normalizedCandidate === $normalizedFilename || str_contains($normalizedCandidate, $normalizedFilename) || str_contains($normalizedFilename, $normalizedCandidate)) {
                return $file;
            }
        }

        return null;
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
