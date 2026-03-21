<?php

namespace App\Services;

use App\Models\Marca;
use Illuminate\Support\Facades\DB;

class MarcaAnalyticsService
{
    /**
     * Get product count by estilista for a given marca.
     */
    public function produtosPorEstilista(Marca $marca): array
    {
        return $marca->produtos()
            ->selectRaw('estilista_id, count(*) as total')
            ->with('estilista')
            ->groupBy('estilista_id')
            ->orderBy('total', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->estilista ? $item->estilista->nome_estilista : 'Sem Estilista' => $item->total];
            })
            ->toArray();
    }

    /**
     * Get product count by localizacao (from last movimentacao) for a given marca.
     */
    public function produtosPorLocalizacao(Marca $marca): array
    {
        $latestMovimentacoes = DB::table('movimentacoes as m1')
            ->select('m1.produto_id', 'm1.localizacao_id')
            ->whereIn('m1.id', function ($query) {
                $query->select(DB::raw('MAX(m2.id)'))
                    ->from('movimentacoes as m2')
                    ->whereRaw('m2.produto_id = m1.produto_id')
                    ->groupBy('m2.produto_id');
            });

        return DB::table('produtos')
            ->joinSub($latestMovimentacoes, 'latest_mov', function ($join) {
                $join->on('produtos.id', '=', 'latest_mov.produto_id');
            })
            ->join('localizacoes', 'latest_mov.localizacao_id', '=', 'localizacoes.id')
            ->where('produtos.marca_id', $marca->id)
            ->whereNull('produtos.deleted_at')
            ->select('localizacoes.nome_localizacao', DB::raw('count(*) as total'))
            ->groupBy('localizacoes.nome_localizacao')
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('total', 'nome_localizacao')
            ->toArray();
    }

    /**
     * Get product count by grupo for a given marca.
     */
    public function produtosPorGrupo(Marca $marca): array
    {
        return $marca->produtos()
            ->selectRaw('grupo_id, count(*) as total')
            ->with('grupoProduto')
            ->groupBy('grupo_id')
            ->orderBy('total', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->grupoProduto ? $item->grupoProduto->descricao : 'Sem Grupo' => $item->total];
            })
            ->toArray();
    }

    /**
     * Get product count by status for a given marca.
     */
    public function produtosPorStatus(Marca $marca): array
    {
        return $marca->produtos()
            ->selectRaw('status_id, count(*) as total')
            ->with('status')
            ->groupBy('status_id')
            ->orderBy('total', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status ? $item->status->descricao : 'Sem Status' => $item->total];
            })
            ->toArray();
    }
}
