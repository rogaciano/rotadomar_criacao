<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Localizacao;
use Illuminate\Support\Facades\DB;

class KanbanController extends Controller
{
    /**
     * Exibir visualização Kanban de produtos por localização
     * Baseado na capacidade mensal - produtos com data_prevista_faccao no período
     */
    public function index(Request $request)
    {
        // Obter filtros
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);

        // Buscar apenas localizações ativas que fazem movimentação
        $localizacoes = Localizacao::where('ativo', true)
            ->where('faz_movimentacao', true)
            ->orderBy('nome_localizacao')
            ->get();

        // Buscar produtos agrupados por localização baseado na data_prevista_faccao
        $produtosPorLocalizacao = [];
        
        foreach ($localizacoes as $localizacao) {
            // Buscar produtos pela data_prevista_faccao em produto_localizacao
            $produtos = Produto::whereHas('localizacoes', function($query) use ($localizacao, $mes, $ano) {
                $query->where('localizacao_id', $localizacao->id)
                      ->whereMonth('data_prevista_faccao', $mes)
                      ->whereYear('data_prevista_faccao', $ano);
            })
            ->with([
                'marca', 
                'grupoProduto', 
                'status',
                'localizacoes' => function($query) use ($localizacao, $mes, $ano) {
                    $query->where('localizacao_id', $localizacao->id)
                          ->whereMonth('data_prevista_faccao', $mes)
                          ->whereYear('data_prevista_faccao', $ano);
                }
            ])
            ->get()
            ->map(function($produto) {
                // Adicionar quantidade_alocada do pivot
                $produto->quantidade_alocada = $produto->localizacoes->sum('pivot.quantidade');
                $produto->data_prevista = $produto->localizacoes->first()->pivot->data_prevista_faccao ?? null;
                return $produto;
            });

            // Só adicionar se tiver produtos
            if ($produtos->count() > 0) {
                $produtosPorLocalizacao[$localizacao->id] = [
                    'localizacao' => $localizacao,
                    'produtos' => $produtos,
                    'total' => $produtos->count()
                ];
            }
        }

        // Gerar lista de meses e anos para os filtros
        $meses = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        $anos = range(now()->year - 2, now()->year + 1);

        return view('kanban.index', compact('produtosPorLocalizacao', 'meses', 'anos', 'mes', 'ano'));
    }
}
