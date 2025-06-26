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
        $query = Produto::with(['marca', 'tecidos', 'estilista', 'grupoProduto', 'status', 'movimentacoes.localizacao']);

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
            $query->whereHas('tecidos', function($q) use ($request) {
                $q->where('tecidos.id', $request->tecido_id);
            });
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

        // Filtro por localização
        if ($request->filled('localizacao_id')) {
            $localizacaoId = $request->localizacao_id;
            
            // Obter IDs dos produtos cuja última movimentação está na localização selecionada
            $subquery = \App\Models\Movimentacao::select('produto_id')
                ->where('localizacao_id', $localizacaoId)
                ->whereIn('id', function($q) {
                    $q->select(\DB::raw('MAX(id)'))
                      ->from('movimentacoes')
                      ->groupBy('produto_id');
                });
                
            $query->whereIn('id', $subquery);
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
        $localizacoes = \App\Models\Localizacao::orderBy('nome_localizacao')->get();

        return view('produtos.index', compact('produtos', 'marcas', 'tecidos', 'estilistas', 'grupos', 'statuses', 'localizacoes'));
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
        // Trim spaces from referencia field if it exists
        if ($request->has('referencia')) {
            $request->merge(['referencia' => trim($request->referencia)]);
        }

        $validator = Validator::make($request->all(), [
            'referencia' => 'required|string|max:50|unique:produtos,referencia',
            'descricao' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'tecidos' => 'required|array|min:1',
            'tecidos.*.tecido_id' => 'required|exists:tecidos,id',
            'tecidos.*.consumo' => 'nullable|numeric|min:0.001',
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

        $data = $request->except('tecidos');

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

        // Criar o produto
        $produto = Produto::create($data);

        // Associar os tecidos ao produto
        if ($request->has('tecidos')) {
            $tecidosData = [];
            foreach ($request->tecidos as $tecido) {
                if (!empty($tecido['tecido_id'])) {
                    $tecidosData[$tecido['tecido_id']] = ['consumo' => $tecido['consumo'] ?? null];
                }
            }
            $produto->tecidos()->sync($tecidosData);
        }

        return redirect()->route('produtos.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produto = Produto::withTrashed()->with(['marca', 'tecidos', 'estilista', 'grupoProduto', 'status'])->findOrFail($id);

        // Carregar as movimentações relacionadas a este produto
        $movimentacoes = \App\Models\Movimentacao::where('produto_id', $id)
            ->with(['localizacao', 'tipo', 'situacao'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('produtos.show', compact('produto', 'movimentacoes'));
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
        // Trim spaces from referencia field if it exists
        if ($request->has('referencia')) {
            $request->merge(['referencia' => trim($request->referencia)]);
        }

        // Trim spaces from descricao field if it exists
        if ($request->has('descricao')) {
            $request->merge(['descricao' => trim($request->descricao)]);
        }

        $produto = Produto::findOrFail($id);

        // Custom validation rules
        $rules = [
            'descricao' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'tecidos' => 'required|array|min:1',
            'tecidos.*.tecido_id' => 'required|exists:tecidos,id',
            'tecidos.*.consumo' => 'nullable|numeric|min:0',
            'estilista_id' => 'required|exists:estilistas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'status_id' => 'required|exists:status,id',
            'ficha_producao' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catalogo_vendas' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];

        // Handle referencia validation separately
        $rules['referencia'] = [
            'required',
            'string',
            'max:50',
            function ($attribute, $value, $fail) use ($id) {
                // Debug values
                $debugInfo = [
                    'id' => $id,
                    'referencia' => $value,
                    'attribute' => $attribute
                ];

                // Check if another product with the same referencia exists (excluding current product)
                $query = Produto::where('referencia', $value)->where('id', '!=', $id);
                $exists = $query->exists();

                if ($exists) {
                    $fail('Atenção: Referência já cadastrada!');
                }
            },
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('produtos.edit', $produto->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('tecidos');

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

        // Atualizar o produto
        $produto->update($data);

        // Atualizar os tecidos associados ao produto
        if ($request->has('tecidos')) {
            $tecidosData = [];
            foreach ($request->tecidos as $tecido) {
                if (!empty($tecido['tecido_id'])) {
                    // Se o consumo estiver vazio, definir como 0
                    $consumo = $tecido['consumo'];
                    if (empty($consumo) || $consumo === null) {
                        $consumo = 0;
                    }
                    $tecidosData[$tecido['tecido_id']] = ['consumo' => $consumo];
                }
            }
            $produto->tecidos()->sync($tecidosData);
        }

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
