<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Produto;
use App\Models\Localizacao;
use App\Models\Tipo;
use App\Models\Situacao;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MovimentacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Movimentacao::with(['produto', 'localizacao', 'tipo', 'situacao']);

        // Aplicar filtros
        if ($request->filled('referencia')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('referencia', 'like', '%' . $request->referencia . '%');
            });
        }

        if ($request->filled('produto_id')) {
            $query->where('produto_id', $request->produto_id);
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->filled('situacao_id')) {
            $query->where('situacao_id', $request->situacao_id);
        }

        if ($request->filled('localizacao_id')) {
            $query->where('localizacao_id', $request->localizacao_id);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_entrada', [$request->data_inicio, $request->data_fim]);
        } elseif ($request->filled('data_inicio')) {
            $query->where('data_entrada', '>=', $request->data_inicio);
        } elseif ($request->filled('data_fim')) {
            $query->where('data_entrada', '<=', $request->data_fim);
        }

        if ($request->filled('comprometido')) {
            $query->where('comprometido', $request->comprometido);
        }

        // Ordenação
        if ($request->filled('sort') && $request->filled('direction')) {
            $sortField = $request->sort;
            $direction = $request->direction;

            // Mapear os campos de ordenação para as colunas corretas no banco de dados
            switch ($sortField) {
                case 'produto':
                    $query->join('produtos', 'movimentacoes.produto_id', '=', 'produtos.id')
                          ->orderBy('produtos.referencia', $direction)
                          ->select('movimentacoes.*'); // Importante para evitar conflitos de colunas
                    break;
                case 'localizacao':
                    $query->join('localizacoes', 'movimentacoes.localizacao_id', '=', 'localizacoes.id')
                          ->orderBy('localizacoes.nome_localizacao', $direction)
                          ->select('movimentacoes.*');
                    break;
                case 'tipo':
                    $query->join('tipos', 'movimentacoes.tipo_id', '=', 'tipos.id')
                          ->orderBy('tipos.descricao', $direction)
                          ->select('movimentacoes.*');
                    break;
                case 'situacao':
                    $query->join('situacoes', 'movimentacoes.situacao_id', '=', 'situacoes.id')
                          ->orderBy('situacoes.descricao', $direction)
                          ->select('movimentacoes.*');
                    break;
                default:
                    // Para campos diretos da tabela movimentacoes
                    if (in_array($sortField, ['data_entrada', 'data_saida', 'data_devolucao', 'comprometido', 'observacao', 'created_at'])) {
                        $query->orderBy($sortField, $direction);
                    } else {
                        // Ordenação padrão se o campo não for reconhecido
                        $query->orderBy('id', 'desc');
                    }
                    break;
            }
        } else {
            // Ordenação padrão se não houver parâmetros de ordenação
            $query->orderBy('id', 'desc');
        }

        $movimentacoes = $query->paginate(10)->withQueryString();

        // Dados para os selects
        $produtos = Produto::orderBy('referencia')->get();
        $situacoes = Situacao::orderBy('descricao')->get();
        $tipos = Tipo::orderBy('descricao')->get();
        $localizacoes = Localizacao::orderBy('nome_localizacao')->get();

        return view('movimentacoes.index', compact('movimentacoes', 'produtos', 'situacoes', 'tipos', 'localizacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $produtos = Produto::all();
        $localizacoes = Localizacao::all();
        $tipos = Tipo::all();
        $situacoes = Situacao::all();

        // Verificar se foi passado um produto_id na URL
        $produto_id = $request->input('produto_id');
        $produto_selecionado = null;

        if ($produto_id) {
            $produto_selecionado = Produto::find($produto_id);
        }

        return view('movimentacoes.create', compact('produtos', 'localizacoes', 'tipos', 'situacoes', 'produto_selecionado', 'produto_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar os dados do formulário
        $validated = $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'localizacao_id' => 'required|exists:localizacoes,id',
            'tipo_id' => 'required|exists:tipos,id',
            'situacao_id' => 'required|exists:situacoes,id',
            'data_entrada' => 'required|date',
            'data_saida' => 'nullable|date|after_or_equal:data_entrada',
            'data_devolucao' => 'nullable|date|after_or_equal:data_entrada',
            'observacao' => 'nullable|string',
        ]);

        try {
            // Criar a movimentação diretamente usando o modelo
            $movimentacao = new Movimentacao();
            $movimentacao->produto_id = $validated['produto_id'];
            $movimentacao->localizacao_id = $validated['localizacao_id'];
            $movimentacao->tipo_id = $validated['tipo_id'];
            $movimentacao->situacao_id = $validated['situacao_id'];
            $movimentacao->data_entrada = $validated['data_entrada'];
            $movimentacao->data_saida = $validated['data_saida'] ?? null;
            $movimentacao->data_devolucao = $validated['data_devolucao'] ?? null;
            $movimentacao->observacao = $validated['observacao'] ?? null;
            $movimentacao->comprometido = 0; // Valor padrão
            $movimentacao->save();

            // Registrar sucesso no log para depuração
            \Illuminate\Support\Facades\Log::info('Movimentação criada com sucesso', [
                'id' => $movimentacao->id,
                'produto_id' => $movimentacao->produto_id
            ]);
        } catch (\Exception $e) {
            // Registrar erro no log para depuração
            \Illuminate\Support\Facades\Log::error('Erro ao criar movimentação', [
                'erro' => $e->getMessage(),
                'dados' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['erro' => 'Erro ao criar movimentação: ' . $e->getMessage()]);
        }

        // Verificar se a movimentação foi criada a partir da página de detalhes do produto
        if ($request->has('redirect_to_produto')) {
            $produto_id = $request->input('redirect_to_produto');
            return redirect()->route('produtos.show', $produto_id)
                ->with('success', 'Movimentação registrada com sucesso.');
        }

        // Redirecionamento padrão para a lista de movimentações
        return redirect()->route('movimentacoes.index')
            ->with('success', 'Movimentação registrada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimentacao $movimentacao)
    {
        $movimentacao->load(['produto', 'localizacao', 'tipo', 'situacao']);
        return view('movimentacoes.show', compact('movimentacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimentacao $movimentacao)
    {
        $produtos = Produto::all();
        $localizacoes = Localizacao::all();
        $tipos = Tipo::all();
        $situacoes = Situacao::all();

        return view('movimentacoes.edit', compact('movimentacao', 'produtos', 'localizacoes', 'tipos', 'situacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimentacao $movimentacao)
    {
        $validated = $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'localizacao_id' => 'required|exists:localizacoes,id',
            'tipo_id' => 'required|exists:tipos,id',
            'situacao_id' => 'required|exists:situacoes,id',
            'data_entrada' => 'required|date',
            'data_saida' => 'nullable|date|after_or_equal:data_entrada',
            'data_devolucao' => 'nullable|date|after_or_equal:data_entrada',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao->update($validated);
        
        // Redirecionar para a mesma página que estava antes
        $page = $request->input('current_page') ? ['page' => $request->input('current_page')] : [];
        
        return redirect()->route('movimentacoes.index', $page)
            ->with('success', 'Movimentação atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimentacao $movimentacao)
    {
        $movimentacao->delete();

        return redirect()->route('movimentacoes.index')
            ->with('success', 'Movimentação excluída com sucesso.');
    }
    
    /**
     * Gera um PDF da movimentação
     */
    public function generatePdf(Movimentacao $movimentacao)
    {
        $movimentacao->load(['produto', 'produto.marca', 'localizacao', 'tipo', 'situacao']);
        
        $pdf = PDF::loadView('movimentacoes.pdf', compact('movimentacao'))
               ->setPaper('a4', 'landscape');
        
        return $pdf->stream('movimentacao-' . $movimentacao->id . '.pdf');
    }
    
    /**
     * Gera um PDF da lista de movimentações com os filtros aplicados
     */
    public function generateListPdf(Request $request)
    {
        // Replicar a lógica de filtros do método index
        $query = Movimentacao::with(['produto', 'localizacao', 'tipo', 'situacao']);

        // Aplicar filtros
        if ($request->filled('referencia')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('referencia', 'like', '%' . $request->referencia . '%');
            });
        }

        if ($request->filled('produto_id')) {
            $query->where('produto_id', $request->produto_id);
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->filled('situacao_id')) {
            $query->where('situacao_id', $request->situacao_id);
        }

        if ($request->filled('localizacao_id')) {
            $query->where('localizacao_id', $request->localizacao_id);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_entrada', [$request->data_inicio, $request->data_fim]);
        } elseif ($request->filled('data_inicio')) {
            $query->where('data_entrada', '>=', $request->data_inicio);
        } elseif ($request->filled('data_fim')) {
            $query->where('data_entrada', '<=', $request->data_fim);
        }

        if ($request->filled('comprometido')) {
            $query->where('comprometido', $request->comprometido);
        }

        // Ordenação
        if ($request->filled('sort') && $request->filled('direction')) {
            $sortField = $request->sort;
            $direction = $request->direction;

            // Mapear os campos de ordenação para as colunas corretas no banco de dados
            switch ($sortField) {
                case 'produto':
                    $query->join('produtos', 'movimentacoes.produto_id', '=', 'produtos.id')
                          ->orderBy('produtos.referencia', $direction)
                          ->select('movimentacoes.*');
                    break;
                case 'localizacao':
                    $query->join('localizacoes', 'movimentacoes.localizacao_id', '=', 'localizacoes.id')
                          ->orderBy('localizacoes.nome_localizacao', $direction)
                          ->select('movimentacoes.*');
                    break;
                case 'tipo':
                    $query->join('tipos', 'movimentacoes.tipo_id', '=', 'tipos.id')
                          ->orderBy('tipos.descricao', $direction)
                          ->select('movimentacoes.*');
                    break;
                case 'situacao':
                    $query->join('situacoes', 'movimentacoes.situacao_id', '=', 'situacoes.id')
                          ->orderBy('situacoes.descricao', $direction)
                          ->select('movimentacoes.*');
                    break;
                default:
                    // Para campos diretos da tabela movimentacoes
                    if (in_array($sortField, ['data_entrada', 'data_saida', 'data_devolucao', 'comprometido', 'observacao', 'created_at'])) {
                        $query->orderBy($sortField, $direction);
                    } else {
                        // Ordenação padrão se o campo não for reconhecido
                        $query->orderBy('id', 'desc');
                    }
                    break;
            }
        } else {
            // Ordenação padrão se não houver parâmetros de ordenação
            $query->orderBy('id', 'desc');
        }
        
        // Verificar o número de registros
        $totalRegistros = $query->count();
        
        // Se houver mais de 200 registros e não foi confirmado, retornar para confirmar
        if ($totalRegistros > 200 && !$request->has('confirmar_pdf')) {
            return redirect()->route('movimentacoes.index', $request->all())
                ->with('warning', "Atenção: Existem {$totalRegistros} registros que serão incluídos no PDF. Isso pode tornar o documento muito grande e lento para carregar. Considere aplicar mais filtros para reduzir o número de registros.")
                ->with('pdf_count', $totalRegistros);
        }
        
        // Buscar todos os registros para o PDF (sem paginação)
        $movimentacoes = $query->get();
        
        // Carregar os dados necessários para os filtros
        $produtos = Produto::orderBy('referencia')->get();
        $situacoes = Situacao::orderBy('descricao')->get();
        $tipos = Tipo::orderBy('descricao')->get();
        $localizacoes = Localizacao::orderBy('nome_localizacao')->get();
        
        // Gerar PDF
        $pdf = PDF::loadView('movimentacoes.pdf_lista', compact('movimentacoes', 'produtos', 
                                                             'situacoes', 'tipos', 'localizacoes', 
                                                             'request', 'totalRegistros'))
                ->setPaper('a4', 'landscape');
        
        return $pdf->stream('lista-movimentacoes.pdf');
    }
}
