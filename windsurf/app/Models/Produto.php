<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Produto extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'referencia',
        'descricao',
        'data_cadastro',
        'marca_id',
        'quantidade',
        'estilista_id',
        'grupo_id',
        'preco_atacado',
        'preco_varejo',
        'status_id',
        'anexo_ficha_producao',
        'anexo_catalogo_vendas'
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'preco_atacado' => 'decimal:2',
        'preco_varejo' => 'decimal:2',
        'quantidade' => 'integer'
    ];

    // Relacionamentos
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    // Relacionamento singular para o primeiro tecido (compatibilidade)
    public function tecido()
    {
        return $this->belongsToMany(Tecido::class, 'produto_tecido')
                    ->withPivot('consumo')
                    ->withTimestamps()
                    ->limit(1);
    }

    // Relacionamento plural para todos os tecidos
    public function tecidos()
    {
        return $this->belongsToMany(Tecido::class, 'produto_tecido')
                    ->withPivot('consumo')
                    ->withTimestamps();
    }

    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    public function grupoProduto()
    {
        return $this->belongsTo(GrupoProduto::class, 'grupo_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class, 'produto_id');
    }
    
    /**
     * Relacionamento com os anexos do produto
     */
    public function anexos()
    {
        return $this->hasMany(ProdutoAnexo::class);
    }

    /**
     * Relacionamento com as variações de cores do produto
     */
    public function cores()
    {
        return $this->hasMany(ProdutoCor::class);
    }

    /**
     * Retorna a quantidade total do produto baseada na soma das quantidades por cor
     */
    public function getQuantidadeTotalPorCoresAttribute()
    {
        return $this->cores()->sum('quantidade');
    }

    /**
     * Retorna as cores disponíveis dos tecidos vinculados ao produto
     */
    public function getCoresDisponiveisAttribute()
    {
        $coresDisponiveis = collect();
        
        foreach ($this->tecidos as $tecido) {
            foreach ($tecido->estoquesCores as $estoqueCor) {
                $coresDisponiveis->push([
                    'cor' => $estoqueCor->cor,
                    'codigo_cor' => $estoqueCor->codigo_cor,
                    'tecido_id' => $tecido->id,
                    'tecido_descricao' => $tecido->descricao
                ]);
            }
        }
        
        return $coresDisponiveis->unique(function ($item) {
            return $item['cor'] . '|' . $item['codigo_cor'];
        });
    }
    
    /**
     * Retorna a localização atual do produto baseada na última movimentação
     */
    public function getLocalizacaoAtualAttribute()
    {
        // Busca a última movimentação do produto ordenada pelo ID mais recente
        $ultimaMovimentacao = $this->movimentacoes()
            ->orderBy('id', 'desc')
            ->with('localizacao')
            ->first();
            
        return $ultimaMovimentacao ? $ultimaMovimentacao->localizacao : null;
    }
    
    /**
     * Retorna o status de conclusão atual do produto baseado na última movimentação
     */
    public function getConcluidoAtualAttribute()
    {
        // Busca a última movimentação do produto ordenada pelo ID mais recente
        $ultimaMovimentacao = $this->movimentacoes()
            ->orderBy('id', 'desc')
            ->first();
            
        return $ultimaMovimentacao ? $ultimaMovimentacao->concluido : null;
    }
    
    /**
     * Configuração do registro de atividades
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
