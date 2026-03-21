<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Movimentacao extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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
        'concluido',
        'created_by'
    ];

    protected $casts = [
        'data_entrada' => 'datetime',
        'data_saida' => 'datetime',
        'data_devolucao' => 'datetime',
        'comprometido' => 'integer',
        'concluido' => 'boolean'
    ];

    // Relacionamentos NÃO são carregados automaticamente.
    // Use ->with() ou ->load() explicitamente nas queries que precisam deles.

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

        // Se for um caminho de rede (começa com // ou \\)
        if (\Illuminate\Support\Str::startsWith($this->anexo, '//') || \Illuminate\Support\Str::startsWith($this->anexo, '\\\\')) {
            // Converter para uma URL válida - usar uma rota específica para servir arquivos de rede
            // Codificar o caminho para evitar problemas com caracteres especiais na URL
            $encodedPath = urlencode($this->anexo);
            return route('arquivo.rede', ['path' => $encodedPath]);
        }

        // Caso contrário, assumir que está no disco public
        return \Illuminate\Support\Facades\Storage::url($this->anexo);
    }


    // Relacionamentos
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id')->withTrashed();
    }

    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class)->withTrashed();
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class)->withTrashed();
    }

    public function situacao()
    {
        return $this->belongsTo(Situacao::class)->withTrashed();
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento com observações
     */
    public function observacoes()
    {
        return $this->hasMany(MovimentacaoObservacao::class);
    }

    /**
     * Accessor para manter compatibilidade com código existente
     * Retorna todas as observações concatenadas
     */
    public function getObservacaoAttribute($value)
    {
        // Usar a relação já carregada se disponível, evitando queries N+1
        $observacoes = $this->relationLoaded('observacoes')
            ? $this->getRelation('observacoes')->sortBy('created_at')
            : $this->observacoes()->orderBy('created_at', 'asc')->get();

        // Se não houver observações relacionadas, retorna o valor original do campo
        if ($observacoes->isEmpty()) {
            return $value;
        }

        // Retorna todas as observações concatenadas com linha divisória e data
        return $observacoes
            ->map(function ($obs) {
                return '[' . $obs->created_at->format('d/m/Y H:i') . '] ' . $obs->observacao;
            })
            ->implode("\n──────────────────────────────────\n");
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
