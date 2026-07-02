<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Produto;
use App\Models\Marca;
use App\Services\EstilistaAnalyticsService;

class Estilista extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Os atributos que sÃ£o atribuÃ­veis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_estilista',
        'ativo',
        'marca_id',
        'user_id',
        'suporte_marca',
        'foto',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Acessor para obter a URL completa da foto do estilista
     *
     * @return string
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        // Retorna uma imagem padrÃ£o caso nÃ£o haja foto
        return asset('images/default-estilista.jpg');
    }

    /**
     * Obter a marca associada a este estilista.
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com os produtos do estilista
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    /**
     * Retorna o total de produtos do estilista
     *
     * @return int
     */
    public function totalProdutos()
    {
        return $this->produtos()->count();
    }

    /**
     * Retorna a contagem de produtos agrupados por marca
     *
     * @return array
     */
    public function produtosPorMarca()
    {
        return app(EstilistaAnalyticsService::class)->produtosPorMarca($this);
    }

    /**
     * Retorna a contagem de produtos agrupados por status
     *
     * @return array
     */
    public function produtosPorStatus()
    {
        return app(EstilistaAnalyticsService::class)->produtosPorStatus($this);
    }

    /**
     * Retorna a contagem de produtos agrupados por grupo
     * Retorna os 10 primeiros grupos e agrupa os demais em 'Outros'
     *
     * @return array
     */
    public function produtosPorGrupo()
    {
        return app(EstilistaAnalyticsService::class)->produtosPorGrupo($this);
    }

    /**
     * Retorna a contagem de produtos agrupados por localizaÃ§Ã£o
     * Retorna as 10 primeiras localizaÃ§Ãµes e agrupa as demais em 'Outros'
     *
     * @return array
     */
    public function produtosPorLocalizacao()
    {
        return app(EstilistaAnalyticsService::class)->produtosPorLocalizacao($this);
    }

    /**
     * Calcula o tempo mÃ©dio desde a criaÃ§Ã£o atÃ© a ativaÃ§Ã£o dos produtos
     * Usa a data da primeira movimentaÃ§Ã£o como data de ativaÃ§Ã£o
     *
     * @return string|null
     */
    /**
     * Retorna os dados mensais de produtos do estilista
     * Ãšltimos 12 meses
     *
     * @return array
     */
    public function produtosPorMes()
    {
        return app(EstilistaAnalyticsService::class)->produtosPorMes($this);
    }

    /**
     * Calcula o tempo mÃ©dio desde a criaÃ§Ã£o atÃ© a ativaÃ§Ã£o dos produtos
     * Usa a data da primeira movimentaÃ§Ã£o como data de ativaÃ§Ã£o
     *
     * @return string|null
     */
    public function tempoMedioAtivacao()
    {
        return app(EstilistaAnalyticsService::class)->tempoMedioAtivacao($this);
    }
}
