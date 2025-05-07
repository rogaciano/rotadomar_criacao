<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Movimentacao;
use App\Models\Tecido;
use App\Models\Estilista;
use App\Models\Marca;
use App\Models\Tipo;
use App\Models\Status;
use App\Models\Situacao;
use App\Models\GrupoProduto;
use App\Models\Localizacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard com estatísticas e dados relevantes.
     */
    public function index()
    {
        // Estatísticas gerais
        $totalProdutos = Produto::count();
        $totalMovimentacoes = Movimentacao::count();
        $movimentacoesHoje = Movimentacao::whereDate('created_at', Carbon::today())->count();
        $totalGrupoProdutos = GrupoProduto::count();

        // Produtos recentes
        $produtosRecentes = Produto::with(['marca', 'status'])
                                ->latest()
                                ->take(5)
                                ->get();

        // Movimentações recentes
        $movimentacoesRecentes = Movimentacao::with(['produto', 'tipo', 'situacao'])
                                    ->latest()
                                    ->take(5)
                                    ->get();


        // Estatísticas por tipo de movimentação
        $estatisticasPorTipo = Movimentacao::select('tipo_id', DB::raw('count(*) as total'))
                                ->groupBy('tipo_id')
                                ->with('tipo')
                                ->get();

        return view('dashboard', compact(
            'totalProdutos',
            'totalMovimentacoes',
            'movimentacoesHoje',
            'produtosRecentes',
            'movimentacoesRecentes',
            'estatisticasPorTipo',
            'totalGrupoProdutos'
        ));
    }
}
