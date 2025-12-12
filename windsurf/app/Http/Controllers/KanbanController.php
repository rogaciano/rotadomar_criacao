<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Localizacao;
use App\Models\DirecionamentoComercial;
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

        // Pode vir string única ou array do multi-select; normalizar para array
        $localizacaoId = $request->get('localizacao_id');
        $localizacaoIds = collect($localizacaoId)->filter()->values()->all();

        // Direcionamento Comercial - agora também multi-select
        $direcionamentoComercialId = $request->get('direcionamento_comercial_id');
        $direcionamentoComercialIds = collect($direcionamentoComercialId)->filter()->values()->all();

        // Lista completa de localizações ativas, com capacidade > 0 e que fazem movimentação (para o filtro)
        $todasLocalizacoes = Localizacao::where('ativo', true)
            ->where('faz_movimentacao', true)
            ->where('capacidade', '>', 0)
            ->orderBy('nome_localizacao')
            ->get();

        // Localizações que serão exibidas nas colunas do Kanban (pode ser filtrada)
        $localizacoesQuery = Localizacao::where('ativo', true)
            ->where('faz_movimentacao', true)
            ->where('capacidade', '>', 0);

        if (!empty($localizacaoIds)) {
            $localizacoesQuery->whereIn('id', $localizacaoIds);
        }

        $localizacoes = $localizacoesQuery
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
                'direcionamentoComercial',
                'localizacoes' => function($query) use ($localizacao, $mes, $ano) {
                    $query->where('localizacao_id', $localizacao->id)
                          ->whereMonth('data_prevista_faccao', $mes)
                          ->whereYear('data_prevista_faccao', $ano);
                }
            ])
            // Filtro opcional por Direcionamento Comercial (agora multi-select)
            ->when(!empty($direcionamentoComercialIds), function($query) use ($direcionamentoComercialIds) {
                $query->whereIn('direcionamento_comercial_id', $direcionamentoComercialIds);
            })
            ->get()
            ->map(function($produto) {
                // Adicionar quantidade_alocada do pivot
                $produto->quantidade_alocada = $produto->localizacoes->sum('pivot.quantidade');
                $produto->data_prevista = $produto->localizacoes->first()->pivot->data_prevista_faccao ?? null;
                $produto->data_envio_faccao = $produto->localizacoes->first()->pivot->data_envio_faccao ?? null;
                $produto->data_retorno_faccao = $produto->localizacoes->first()->pivot->data_retorno_faccao ?? null;
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

        // Lista de direcionamentos comerciais para o filtro
        $direcionamentosComerciais = DirecionamentoComercial::where('ativo', true)
            ->orderBy('descricao')
            ->get();

        return view('kanban.index', compact(
            'produtosPorLocalizacao',
            'meses',
            'anos',
            'mes',
            'ano',
            'todasLocalizacoes',
            'localizacaoIds',
            'direcionamentosComerciais',
            'direcionamentoComercialIds'
        ));
    }
}
