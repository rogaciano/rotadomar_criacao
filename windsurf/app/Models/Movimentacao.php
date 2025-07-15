<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimentacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimentacoes';

    protected $fillable = [
        'comprometido',
        'produto_id',
        'localizacao_id',
        'data_entrada',
        'data_saida',
        'data_devolucao',
        'tipo_id',
        'situacao_id',
        'observacao',
        'anexo',
        'concluido'
    ];

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida' => 'date',
        'data_devolucao' => 'date',
        'comprometido' => 'integer',
        'concluido' => 'boolean'
    ];

    // Definindo relacionamentos para serem carregados automaticamente
    protected $with = ['produto', 'localizacao', 'tipo', 'situacao'];

    // Accessor para URL do anexo
    public function getAnexoUrlAttribute()
    {
        if (!$this->anexo) {
            return null;
        }
        // Se caminho começa com uploads/, usar asset direto
        if (\Illuminate\Support\Str::startsWith($this->anexo, 'uploads/')) {
            return asset($this->anexo);
        }
        // Caso contrário, assumir que está no disco public
        return \Illuminate\Support\Facades\Storage::url($this->anexo);
    }

    // Relacionamentos
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function situacao()
    {
        return $this->belongsTo(Situacao::class);
    }
}
