<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdutoComponente extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'produto_componentes';

    protected $fillable = [
        'produto_combinacao_id',
        'tecido_id',
        'cor',
        'codigo_cor',
        'consumo',
        'porcentagem',
        'observacoes'
    ];

    protected $casts = [
        'consumo' => 'decimal:2',
        'porcentagem' => 'decimal:2'
    ];
    
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function (ProdutoComponente $componente) {
            // Se tiver tecido_id e cor, verificar se existe na tabela tecido_cor_estoques
            if ($componente->tecido_id && $componente->cor) {
                $estoque = TecidoCorEstoque::firstOrCreate(
                    [
                        'tecido_id' => $componente->tecido_id,
                        'cor' => $componente->cor,
                    ],
                    [
                        'codigo_cor' => $componente->codigo_cor,
                        'quantidade' => 0,
                        'data_atualizacao' => now(),
                    ]
                );
                
                // Se o código da cor foi atualizado no componente, atualizar também no estoque
                if ($componente->codigo_cor && $componente->codigo_cor !== $estoque->codigo_cor) {
                    $estoque->update(['codigo_cor' => $componente->codigo_cor]);
                }
            }
        });
    }

    /**
     * Relacionamento com a combinação do produto
     */
    public function produtoCombinacao()
    {
        return $this->belongsTo(ProdutoCombinacao::class);
    }

    /**
     * Relacionamento com o tecido
     */
    public function tecido()
    {
        return $this->belongsTo(Tecido::class);
    }
    
    /**
     * Obter o estoque de cor do tecido associado
     */
    public function tecidoCorEstoque()
    {
        return TecidoCorEstoque::where('tecido_id', $this->tecido_id)
            ->where('cor', $this->cor)
            ->first();
    }
    
    /**
     * Obter a quantidade em estoque para esta cor de tecido
     */
    public function getEstoqueAttribute()
    {
        $estoque = $this->tecidoCorEstoque();
        return $estoque ? $estoque->quantidade : 0;
    }
    
    /**
     * Obter a necessidade de tecido para esta cor
     */
    public function getNecessidadeAttribute()
    {
        $estoque = $this->tecidoCorEstoque();
        return $estoque ? $estoque->necessidade : 0;
    }
    
    /**
     * Obter o saldo (estoque - necessidade) para esta cor de tecido
     */
    public function getSaldoAttribute()
    {
        $estoque = $this->tecidoCorEstoque();
        return $estoque ? $estoque->saldo : 0;
    }
    
    /**
     * Obter a quantidade possível de produção para esta cor de tecido
     */
    public function getProducaoPossivelAttribute()
    {
        if (!$this->tecido_id || !$this->cor || !$this->consumo || $this->consumo <= 0) {
            return 0;
        }
        
        $estoque = $this->tecidoCorEstoque();
        if (!$estoque || $estoque->quantidade <= 0) {
            return 0;
        }
        
        return floor($estoque->quantidade / $this->consumo);
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
