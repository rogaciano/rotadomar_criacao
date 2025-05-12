<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Produto;
use App\Models\Localizacao;
use App\Models\Tipo;
use App\Models\Situacao;
use Illuminate\Http\Request;

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
            $query->where('referencia', 'like', '%' . $request->referencia . '%');
        }

        if ($request->filled('produto_id')) {
            $produto = Produto::find($request->produto_id);
            if ($produto) {
                $query->where('referencia', $produto->referencia);
            }
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
        $query->latest();

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
    public function create()
    {
        $produtos = Produto::all();
        $localizacoes = Localizacao::all();
        $tipos = Tipo::all();
        $situacoes = Situacao::all();

        return view('movimentacoes.create', compact('produtos', 'localizacoes', 'tipos', 'situacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'localizacao_id' => 'required|exists:localizacoes,id',
            'tipo_id' => 'required|exists:tipos,id',
            'situacao_id' => 'required|exists:situacoes,id',
            'data_entrada' => 'required|date',
            'data_saida' => 'nullable|date|after_or_equal:data_entrada',
            'observacao' => 'nullable|string',
        ]);

        // Obter a referência do produto selecionado
        $produto = Produto::findOrFail($request->produto_id);
        
        // Garantir que o produto_id está incluído nos dados
        $dados = $validated;
        $dados['produto_id'] = $request->produto_id;

        // Criar a movimentação
        $movimentacao = Movimentacao::create($dados);

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
            'observacao' => 'nullable|string',
        ]);

        $movimentacao->update($validated);

        return redirect()->route('movimentacoes.index')
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
}
