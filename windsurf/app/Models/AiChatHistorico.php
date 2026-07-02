<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChatHistorico extends Model
{
    protected $table = 'ai_chat_historico';

    protected $fillable = [
        'user_id',
        'pergunta',
        'sql_gerado',
        'resposta',
        'util',
    ];

    protected $casts = [
        'util' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna os últimos exemplos positivos para uso como few-shot no prompt.
     */
    public static function exemplosPosistivos(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('util', true)
            ->whereNotNull('sql_gerado')
            ->latest()
            ->limit($limit)
            ->get(['pergunta', 'sql_gerado']);
    }

    /**
     * Retorna os últimos exemplos negativos para uso como exemplos a evitar no prompt.
     */
    public static function exemplosNegativos(int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('util', false)
            ->whereNotNull('sql_gerado')
            ->latest()
            ->limit($limit)
            ->get(['pergunta', 'sql_gerado']);
    }
}
