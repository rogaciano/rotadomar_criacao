<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Marca;
use App\Models\Tecido;
use App\Models\Estilista;
use App\Models\GrupoProduto;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produto::with(['marca', 'tecido', 'estilista', 'grupoProduto', 'status']);

        // Filtros
        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . $request->referencia . '%');
        }

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }

        if ($request->filled('tecido_id')) {
            $query->where('tecido_id', $request->tecido_id);
        }

        if ($request->filled('estilista_id')) {
            $query->where('estilista_id', $request->estilista_id);
        }

        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // Incluir excluídos se solicitado
        if ($request->filled('incluir_excluidos')) {
            $query->withTrashed();
        }

        $produtos = $query->orderBy('referencia')->paginate(10);

        // Buscar dados para os selects de filtro
        $marcas = Marca::orderBy('nome_marca')->get();
        $tecidos = Tecido::orderBy('descricao')->get();
        $estilistas = Estilista::orderBy('nome_estilista')->get();
        $grupos = GrupoProduto::orderBy('descricao')->get();
        $statuses = Status::orderBy('descricao')->get();

        return view('produtos.index', compact('produtos', 'marcas', 'tecidos', 'estilistas', 'grupos', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Buscar dados para os selects
        $marcas = Marca::where('ativo', true)->orderBy('nome_marca')->get();
        $tecidos = Tecido::where('ativo', true)->orderBy('descricao')->get();
        $estilistas = Estilista::where('ativo', true)->orderBy('nome_estilista')->get();
        $grupos = GrupoProduto::where('ativo', true)->orderBy('descricao')->get();
        $statuses = Status::where('ativo', true)->orderBy('descricao')->get();

        return view('produtos.create', compact('marcas', 'tecidos', 'estilistas', 'grupos', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referencia' => 'required|string|max:50|unique:produtos,referencia',
            'descricao' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'tecido_id' => 'required|exists:tecidos,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'status_id' => 'required|exists:status,id',
            'ficha_producao' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catalogo_vendas' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('produtos.create')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Upload da ficha de produção, se fornecida
        if ($request->hasFile('ficha_producao')) {
            $ficha = $request->file('ficha_producao');
            $fichaName = time() . '_ficha_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $ficha->getClientOriginalName());
            
            // Criar diretório se não existir
            $directory = storage_path('app/public/fichas');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $fichaPath = $ficha->storeAs('fichas', $fichaName, 'public');
            $data['anexo_ficha_producao'] = $fichaPath;
        }

        // Upload do catálogo de vendas, se fornecido
        if ($request->hasFile('catalogo_vendas')) {
            $catalogo = $request->file('catalogo_vendas');
            $catalogoName = time() . '_catalogo_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $catalogo->getClientOriginalName());
            
            // Criar diretório se não existir
            $directory = storage_path('app/public/catalogos');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $catalogoPath = $catalogo->storeAs('catalogos', $catalogoName, 'public');
            $data['anexo_catalogo_vendas'] = $catalogoPath;
        }

        Produto::create($data);

        return redirect()->route('produtos.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produto = Produto::withTrashed()->with(['marca', 'tecido', 'estilista', 'grupoProduto', 'status'])->findOrFail($id);
        return view('produtos.show', compact('produto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produto = Produto::findOrFail($id);

        // Buscar dados para os selects
        $marcas = Marca::where('ativo', true)->orderBy('nome_marca')->get();
        $tecidos = Tecido::where('ativo', true)->orderBy('descricao')->get();
        $estilistas = Estilista::where('ativo', true)->orderBy('nome_estilista')->get();
        $grupos = GrupoProduto::where('ativo', true)->orderBy('descricao')->get();
        $statuses = Status::where('ativo', true)->orderBy('descricao')->get();

        return view('produtos.edit', compact('produto', 'marcas', 'tecidos', 'estilistas', 'grupos', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produto = Produto::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'referencia' => 'required|string|max:50|unique:produtos,referencia,' . $id,
            'descricao' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'tecido_id' => 'required|exists:tecidos,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'status_id' => 'required|exists:status,id',
            'ficha_producao' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catalogo_vendas' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('produtos.edit', $produto->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Upload da ficha de produção, se fornecida
        if ($request->hasFile('ficha_producao')) {
            // Remover ficha anterior, se existir
            if ($produto->anexo_ficha_producao) {
                Storage::disk('public')->delete($produto->anexo_ficha_producao);
            }

            $ficha = $request->file('ficha_producao');
            $fichaName = time() . '_ficha_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $ficha->getClientOriginalName());
            
            // Criar diretório se não existir
            $directory = storage_path('app/public/fichas');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $fichaPath = $ficha->storeAs('fichas', $fichaName, 'public');
            $data['anexo_ficha_producao'] = $fichaPath;
        }

        // Upload do catálogo de vendas, se fornecido
        if ($request->hasFile('catalogo_vendas')) {
            // Remover catálogo anterior, se existir
            if ($produto->anexo_catalogo_vendas) {
                Storage::disk('public')->delete($produto->anexo_catalogo_vendas);
            }

            $catalogo = $request->file('catalogo_vendas');
            $catalogoName = time() . '_catalogo_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $catalogo->getClientOriginalName());
            
            // Criar diretório se não existir
            $directory = storage_path('app/public/catalogos');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $catalogoPath = $catalogo->storeAs('catalogos', $catalogoName, 'public');
            $data['anexo_catalogo_vendas'] = $catalogoPath;
        }

        $produto->update($data);

        return redirect()->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produto = Produto::withTrashed()->findOrFail($id);

        if ($produto->trashed()) {
            // Restaurar
            $produto->restore();
            $message = 'Produto restaurado com sucesso!';
        } else {
            // Excluir
            $produto->delete();
            $message = 'Produto excluído com sucesso!';
        }

        return redirect()->route('produtos.index')
            ->with('success', $message);
    }
}
