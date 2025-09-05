<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProdutoCombinacao extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'produto_combinacao';

    protected $fillable = [
        'produto_id',
        'descricao',
        'quantidade_pretendida',
        'observacoes'
    ];

    protected $casts = [
        'quantidade_pretendida' => 'decimal:2'
    ];

    /**
     * Relacionamento com o produto
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Relacionamento com os componentes
     */
    public function componentes()
    {
        return $this->hasMany(ProdutoComponente::class);
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
