<?php

namespace App\Services;

use App\Models\Estilista;

class EstilistaAnalyticsService
{
    /**
     * Retorna a contagem de produtos agrupados por marca.
     */
    public function produtosPorMarca(Estilista $estilista): array
    {
        return $estilista->produtos()
            ->selectRaw('marca_id, count(*) as total')
            ->with('marca')
            ->groupBy('marca_id')
            ->orderBy('total', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->marca ? $item->marca->nome_marca : 'Sem Marca' => $item->total];
            })
            ->toArray();
    }

    /**
     * Retorna a contagem de produtos agrupados por status.
     */
    public function produtosPorStatus(Estilista $estilista): array
    {
        return $estilista->produtos()
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

    /**
     * Retorna a contagem de produtos agrupados por grupo (top 10 + Outros).
     */
    public function produtosPorGrupo(Estilista $estilista): array
    {
        $result = $estilista->produtos()
            ->selectRaw('grupo_id, count(*) as total')
            ->with('grupoProduto')
            ->groupBy('grupo_id')
            ->orderBy('total', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->grupoProduto ? $item->grupoProduto->descricao : 'Sem Grupo' => $item->total];
            })
            ->toArray();

        arsort($result);

        $top10 = array_slice($result, 0, 10, true);
        $outros = array_slice($result, 10, null, true);

        if (count($outros) > 0) {
            $top10['Outros'] = array_sum($outros);
        }

        return $top10;
    }

    /**
     * Retorna a contagem de produtos agrupados por localização (top 10 + Outros).
     */
    public function produtosPorLocalizacao(Estilista $estilista): array
    {
        $result = [];
        $produtos = $estilista->produtos()->with('movimentacoes.localizacao')->get();

        foreach ($produtos as $produto) {
            $ultimaMovimentacao = $produto->movimentacoes->sortByDesc('id')->first();

            if ($ultimaMovimentacao && $ultimaMovimentacao->localizacao) {
                $localizacao = $ultimaMovimentacao->localizacao->nome_localizacao;
                if (!isset($result[$localizacao])) {
                    $result[$localizacao] = 0;
                }
                $result[$localizacao]++;
            }
        }

        arsort($result);

        $top10 = array_slice($result, 0, 10, true);
        $outros = array_slice($result, 10, null, true);

        if (count($outros) > 0) {
            $top10['Outros'] = array_sum($outros);
        }

        return $top10;
    }

    /**
     * Retorna os dados mensais de produtos do estilista (últimos 12 meses).
     */
    public function produtosPorMes(Estilista $estilista): array
    {
        $data = [];
        $labels = [];
        $valores = [];

        for ($i = 11; $i >= 0; $i--) {
            $dataAtual = now()->subMonths($i);
            $mesAno = $dataAtual->format('m/Y');
            $labels[] = $dataAtual->translatedFormat('M/Y');
            $data[$mesAno] = 0;
        }

        $produtos = $estilista->produtos()
            ->select('id', 'created_at')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('m/Y');
            });

        foreach ($data as $mesAno => $valor) {
            if (isset($produtos[$mesAno])) {
                $data[$mesAno] = $produtos[$mesAno]->count();
            }
            $valores[] = $data[$mesAno];
        }

        return [
            'labels' => $labels,
            'data' => $valores,
            'total' => array_sum($valores),
        ];
    }

    /**
     * Calcula o tempo médio desde a criação até a ativação dos produtos.
     */
    public function tempoMedioAtivacao(Estilista $estilista): ?string
    {
        $produtos = $estilista->produtos()
            ->with(['movimentacoes' => function ($query) {
                $query->orderBy('data_entrada', 'asc')
                      ->limit(1);
            }])
            ->get();

        if ($produtos->isEmpty()) {
            return null;
        }

        $totalDias = 0;
        $count = 0;

        foreach ($produtos as $produto) {
            $primeiraMovimentacao = $produto->movimentacoes->sortBy('data_entrada')->first();

            if ($primeiraMovimentacao && $produto->data_cadastro) {
                $diferenca = $produto->data_cadastro->diffInDays($primeiraMovimentacao->data_entrada);
                $totalDias += $diferenca;
                $count++;
            }
        }

        if ($count === 0) {
            return null;
        }

        $mediaDias = $totalDias / $count;

        if ($mediaDias < 1) {
            return 'Menos de 1 dia';
        }

        return round($mediaDias) . ' dias';
    }
}
