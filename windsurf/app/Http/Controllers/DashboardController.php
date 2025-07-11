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
        
        // Estatísticas de produtos por ano
        $anoAtual = Carbon::now()->year;
        $dataAtual = Carbon::now();
        
        // Produtos cadastrados no ano atual
        $produtosAnoAtual = Produto::whereYear('created_at', $anoAtual)->count();
        
        // Produtos cadastrados no ano passado
        $produtosAnoPassado = Produto::whereYear('created_at', $anoAtual - 1)->count();
        
        // Produtos cadastrados no ano atual até a data atual
        $produtosAnoAtualAteHoje = Produto::whereYear('created_at', $anoAtual)
            ->whereDate('created_at', '<=', $dataAtual)
            ->count();
        
        // Produtos cadastrados no ano passado até a mesma data
        $dataAnoPassado = Carbon::now()->subYear();
        $produtosAnoPassadoAteHoje = Produto::whereYear('created_at', $anoAtual - 1)
            ->whereDate('created_at', '<=', $dataAnoPassado)
            ->count();
        
        // Calcular variação percentual
        $variacaoPercentual = $produtosAnoPassadoAteHoje > 0 
            ? round((($produtosAnoAtualAteHoje - $produtosAnoPassadoAteHoje) / $produtosAnoPassadoAteHoje) * 100, 1) 
            : 100;
        
        // Estatísticas dos últimos 5 anos
        $estatisticasUltimos5Anos = [];
        for ($i = 0; $i < 5; $i++) {
            $ano = $anoAtual - $i;
            $estatisticasUltimos5Anos[$ano] = Produto::whereYear('created_at', $ano)->count();
        }
        
        // Comparação do ano corrente com anos anteriores até a data atual
        $comparacaoAnos = [];
        $mesAtual = Carbon::now()->month;
        $diaAtual = Carbon::now()->day;
        
        for ($i = 0; $i < 3; $i++) {
            $ano = $anoAtual - $i;
            $dataLimite = Carbon::create($ano, $mesAtual, $diaAtual);
            $comparacaoAnos[$ano] = Produto::whereYear('created_at', $ano)
                ->whereDate('created_at', '<=', $dataLimite)
                ->count();
        }
        
        // Projeção para o ano atual
        $diasDecorridos = Carbon::now()->dayOfYear;
        $diasNoAno = Carbon::now()->isLeapYear() ? 366 : 365;
        $projecaoProdutosAnoAtual = $diasNoAno > 0 
            ? round(($produtosAnoAtualAteHoje / $diasDecorridos) * $diasNoAno) 
            : 0;

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
                                    
        // Produtos do setor do usuário autenticado
        $produtosDoSetor = collect();
        $localizacaoUsuario = auth()->user()->localizacao_id;
        
        if ($localizacaoUsuario) {
            // Busca produtos que estão na localização do usuário (setor) através da última movimentação
            $produtosDoSetor = Produto::whereHas('movimentacoes', function($query) use ($localizacaoUsuario) {
                $query->where('localizacao_id', $localizacaoUsuario)
                      ->whereIn('id', function($subquery) {
                          $subquery->selectRaw('MAX(id)')
                                   ->from('movimentacoes')
                                   ->groupBy('produto_id');
                      });
            })
            ->with(['marca', 'status', 'estilista', 'grupoProduto'])
            ->latest()
            ->take(10)
            ->get();
        }


        // Estatísticas por tipo de movimentação
        $estatisticasPorTipo = Movimentacao::select('tipo_id', DB::raw('count(*) as total'))
                                ->groupBy('tipo_id')
                                ->with('tipo')
                                ->get();
                                
        // Estatísticas de produtos ativos por estilista
        $statusAtivo = Status::where('descricao', 'Ativo')->first();
        $statusAtivoId = $statusAtivo ? $statusAtivo->id : null;
        
        $produtosAtivosPorEstilista = [];
        if ($statusAtivoId) {
            // Obter todos os estilistas com produtos ativos
            $todosEstilistas = Produto::select('estilistas.nome_estilista', DB::raw('count(*) as total'))
                                ->join('estilistas', 'produtos.estilista_id', '=', 'estilistas.id')
                                ->where('produtos.status_id', $statusAtivoId)
                                ->groupBy('estilistas.nome_estilista')
                                ->orderByDesc('total')
                                ->get();
            
            // Separar os top 10 estilistas
            $top10Estilistas = $todosEstilistas->take(10);
            
            // Calcular o total de produtos dos estilistas restantes
            $outrosTotal = $todosEstilistas->skip(10)->sum('total');
            
            // Criar a coleção final com os top 10 + outros
            $produtosAtivosPorEstilista = $top10Estilistas;
            
            // Adicionar a categoria "Outros" se houver estilistas além dos top 10
            if ($outrosTotal > 0) {
                $produtosAtivosPorEstilista->push([
                    'nome_estilista' => 'Outros',
                    'total' => $outrosTotal
                ]);
            }
        }
        
        // Estatísticas de produtos ativos por estilista ao longo do tempo (últimos 12 meses)
        $produtosAtivosPorMes = [];
        $mesesLabels = [];
        
        if ($statusAtivoId) {
            for ($i = 11; $i >= 0; $i--) {
                $data = Carbon::now()->subMonths($i);
                $mes = $data->format('M Y');
                $mesesLabels[] = $mes;
                
                $produtosAtivosNoMes = Produto::where('status_id', $statusAtivoId)
                                        ->whereYear('created_at', $data->year)
                                        ->whereMonth('created_at', $data->month)
                                        ->count();
                                        
                $produtosAtivosPorMes[] = $produtosAtivosNoMes;
            }
        }

        return view('dashboard', compact(
            'totalProdutos',
            'totalMovimentacoes',
            'movimentacoesHoje',
            'totalGrupoProdutos',
            'produtosAnoAtual',
            'produtosAnoPassado',
            'produtosAnoAtualAteHoje',
            'produtosAnoPassadoAteHoje',
            'variacaoPercentual',
            'estatisticasUltimos5Anos',
            'comparacaoAnos',
            'projecaoProdutosAnoAtual',
            'produtosRecentes',
            'movimentacoesRecentes',
            'estatisticasPorTipo',
            'produtosAtivosPorEstilista',
            'produtosAtivosPorMes',
            'mesesLabels',
            'produtosDoSetor'
        ));
    }
    
    /**
     * Busca dados de produtos por ano para atualização dinâmica dos gráficos.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDadosPorAno(Request $request)
    {
        $ano = $request->input('ano', Carbon::now()->year);
        
        // Estatísticas de produtos ativos por estilista para o ano selecionado
        $statusAtivo = Status::where('descricao', 'Ativo')->first();
        $statusAtivoId = $statusAtivo ? $statusAtivo->id : null;
        
        $produtosAtivosPorEstilista = [];
        if ($statusAtivoId) {
            // Obter todos os estilistas com produtos ativos do ano selecionado
            $todosEstilistas = Produto::select('estilistas.nome_estilista', DB::raw('count(*) as total'))
                                ->join('estilistas', 'produtos.estilista_id', '=', 'estilistas.id')
                                ->where('produtos.status_id', $statusAtivoId)
                                ->whereYear('produtos.created_at', $ano)
                                ->groupBy('estilistas.nome_estilista')
                                ->orderByDesc('total')
                                ->get();
            
            // Separar os top 10 estilistas
            $top10Estilistas = $todosEstilistas->take(10);
            
            // Calcular o total de produtos dos estilistas restantes
            $outrosTotal = $todosEstilistas->skip(10)->sum('total');
            
            // Criar a coleção final com os top 10 + outros
            $produtosAtivosPorEstilista = $top10Estilistas;
            
            // Adicionar a categoria "Outros" se houver estilistas além dos top 10
            if ($outrosTotal > 0) {
                $produtosAtivosPorEstilista->push([
                    'nome_estilista' => 'Outros',
                    'total' => $outrosTotal
                ]);
            }
        }
        
        // Estatísticas de produtos por mês para o ano selecionado
        $produtosPorMes = [];
        $mesesLabels = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $data = Carbon::createFromDate($ano, $i, 1);
            $mes = $data->format('M');
            $mesesLabels[] = $mes;
            
            $produtosNoMes = Produto::whereYear('created_at', $ano)
                            ->whereMonth('created_at', $i)
                            ->count();
                            
            $produtosPorMes[] = $produtosNoMes;
        }
        
        // Produtos ativos por mês para o ano selecionado
        $produtosAtivosPorMes = [];
        
        if ($statusAtivoId) {
            for ($i = 1; $i <= 12; $i++) {
                $produtosAtivosNoMes = Produto::where('status_id', $statusAtivoId)
                                        ->whereYear('created_at', $ano)
                                        ->whereMonth('created_at', $i)
                                        ->count();
                                        
                $produtosAtivosPorMes[] = $produtosAtivosNoMes;
            }
        }
        
        return response()->json([
            'produtosAtivosPorEstilista' => [
                'labels' => $produtosAtivosPorEstilista->pluck('nome_estilista')->toArray(),
                'data' => $produtosAtivosPorEstilista->pluck('total')->toArray(),
            ],
            'produtosPorMes' => [
                'labels' => $mesesLabels,
                'data' => $produtosPorMes,
            ],
            'produtosAtivosPorMes' => [
                'labels' => $mesesLabels,
                'data' => $produtosAtivosPorMes,
            ],
            'ano' => $ano
        ]);
    }
}
