<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sugestao extends Model
{
    use HasFactory;

    public const STATUS_NAO_LIDA = 'nao_lida';
    public const STATUS_LIDA = 'lida';
    public const STATUS_EM_ANALISE = 'em_analise';
    public const STATUS_ACEITO = 'aceito';
    public const STATUS_NEGADO = 'negado';

    public const STATUS_VALIDOS = [
        self::STATUS_NAO_LIDA,
        self::STATUS_LIDA,
        self::STATUS_EM_ANALISE,
        self::STATUS_ACEITO,
        self::STATUS_NEGADO,
    ];

    protected $table = 'sugestoes';

    protected $fillable = [
        'user_id',
        'localizacao_id',
        'assunto',
        'texto',
        'status',
        'lido_por_user_id',
        'lido_em',
    ];

    protected $casts = [
        'lido_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function localizacao(): BelongsTo
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_id');
    }

    public function lidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lido_por_user_id');
    }

    public function scopeVisiveisPara(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();

        return $query->where(function (Builder $subQuery) use ($user, $localizacoesPermitidas) {
            $subQuery->where('user_id', $user->id);

            if (!empty($localizacoesPermitidas)) {
                $subQuery->orWhereIn('localizacao_id', $localizacoesPermitidas);
            }
        });
    }

    public function getStatusLabelAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_NAO_LIDA:
                return 'Não lida';
            case self::STATUS_LIDA:
                return 'Lida';
            case self::STATUS_EM_ANALISE:
                return 'Em análise';
            case self::STATUS_ACEITO:
                return 'Aceito';
            case self::STATUS_NEGADO:
                return 'Negado';
            default:
                return $this->status;
        }
    }
}
