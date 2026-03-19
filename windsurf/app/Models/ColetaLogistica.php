<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColetaLogistica extends Model
{
    use HasFactory;

    protected $table = 'coletas_logisticas';

    const STATUS_AGENDADO = 'agendado';
    const STATUS_EM_TRANSITO = 'em_transito';
    const STATUS_FINALIZADO = 'finalizado';
    const STATUS_CANCELADO = 'cancelado';

    protected $fillable = [
        'produto_localizacao_id',
        'motorista_user_id',
        'veiculo_id',
        'destino_localizacao_id',
        'inicio_previsto_em',
        'retorno_previsto_em',
        'chegada_origem_em',
        'recebido_destino_em',
        'status',
        'observacao_motorista',
        'observacao_origem',
        'observacao_destino',
    ];

    protected $casts = [
        'inicio_previsto_em' => 'datetime',
        'retorno_previsto_em' => 'datetime',
        'chegada_origem_em' => 'datetime',
        'recebido_destino_em' => 'datetime',
    ];

    /**
     * Produto-localização associado
     */
    public function produtoLocalizacao()
    {
        return $this->belongsTo(ProdutoLocalizacao::class);
    }

    /**
     * Motorista (usuário) responsável pela coleta
     */
    public function motorista()
    {
        return $this->belongsTo(User::class, 'motorista_user_id');
    }

    /**
     * Veículo utilizado na coleta
     */
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    /**
     * Localização de destino da coleta
     */
    public function destinoLocalizacao()
    {
        return $this->belongsTo(Localizacao::class, 'destino_localizacao_id');
    }

    /**
     * Scope: coletas ativas (agendado ou em_transito)
     */
    public function scopeAtivas($query)
    {
        return $query->whereIn('status', [self::STATUS_AGENDADO, self::STATUS_EM_TRANSITO]);
    }

    /**
     * Verifica se a coleta está ativa
     */
    public function isAtiva(): bool
    {
        return in_array($this->status, [self::STATUS_AGENDADO, self::STATUS_EM_TRANSITO]);
    }

    /**
     * Verifica se pode ser cancelada (só em agendado)
     */
    public function podeCancelar(): bool
    {
        return $this->status === self::STATUS_AGENDADO;
    }

    /**
     * Verifica conflito de agenda para motorista
     */
    public static function temConflitoMotorista(int $motoristaId, string $inicio, string $retorno, ?int $excluirId = null): bool
    {
        return static::where('motorista_user_id', $motoristaId)
            ->ativas()
            ->where('inicio_previsto_em', '<', $retorno)
            ->where('retorno_previsto_em', '>', $inicio)
            ->when($excluirId, function ($q) use ($excluirId) {
                return $q->where('id', '!=', $excluirId);
            })
            ->exists();
    }

    /**
     * Verifica conflito de agenda para veículo
     */
    public static function temConflitoVeiculo(int $veiculoId, string $inicio, string $retorno, ?int $excluirId = null): bool
    {
        return static::where('veiculo_id', $veiculoId)
            ->ativas()
            ->where('inicio_previsto_em', '<', $retorno)
            ->where('retorno_previsto_em', '>', $inicio)
            ->when($excluirId, function ($q) use ($excluirId) {
                return $q->where('id', '!=', $excluirId);
            })
            ->exists();
    }

    /**
     * Verifica se já existe coleta ativa para o produto_localizacao
     */
    public static function temColetaAtiva(int $produtoLocalizacaoId, ?int $excluirId = null): bool
    {
        return static::where('produto_localizacao_id', $produtoLocalizacaoId)
            ->ativas()
            ->when($excluirId, function ($q) use ($excluirId) {
                return $q->where('id', '!=', $excluirId);
            })
            ->exists();
    }
}
