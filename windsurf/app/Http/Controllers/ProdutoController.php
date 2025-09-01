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
use Barryvdh\DomPDF\Facade\Pdf;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->canRead('produtos')) { abort(403); }
        // Verifica se é uma nova pesquisa ou se deve usar os filtros da sessão
        $useSessionFilters = !$request->hasAny([
            'referencia', 'descricao', 'marca_id', 'marca', 'tecido_id', 
            'estilista_id', 'estilista', 'grupo_id', 'grupo', 'status_id', 
            'status', 'localizacao_id', 'localizacao', 'incluir_excluidos',
            'data_inicio', 'data_fim', 'data_prevista_inicio', 'data_prevista_fim', 'concluido'
        ]) && $request->method() === 'GET' && !$request->ajax();
        
        // Se for uma nova pesquisa, salva os filtros na sessão
        if (!$useSessionFilters && $request->method() === 'GET') {
            $filters = $request->only([
                'referencia', 'descricao', 'marca_id', 'marca', 'tecido_id', 
                'estilista_id', 'estilista', 'grupo_id', 'grupo', 'status_id', 
                'status', 'localizacao_id', 'localizacao', 'incluir_excluidos',
                'data_inicio', 'data_fim', 'data_prevista_inicio', 'data_prevista_fim', 'concluido'
            ]);
            session(['produtos_filters' => $filters]);
        }
        
        // Se não há filtros na requisição, usa os da sessão
        $filters = $useSessionFilters ? session('produtos_filters', []) : $request->all();
        
        $query = Produto::with(['marca', 'tecidos', 'estilista', 'grupoProduto', 'status', 'movimentacoes.localizacao']);

        // Filtros
        if (!empty($filters['referencia'])) {
            $query->where('referencia', 'like', '%' . $filters['referencia'] . '%');
        }

        if (!empty($filters['descricao'])) {
            $query->where('descricao', 'like', '%' . $filters['descricao'] . '%');
        }

        // Filtro por marca (aceita ID ou nome)
        if (!empty($filters['marca_id'])) {
            $query->where('marca_id', $filters['marca_id']);
        } elseif (!empty($filters['marca'])) {
            $marcaId = Marca::where('nome_marca', $filters['marca'])->value('id');
            if ($marcaId) {
                $query->where('marca_id', $marcaId);
            }
        }

        if (!empty($filters['tecido_id'])) {
            $query->whereHas('tecidos', function($q) use ($filters) {
                $q->where('tecidos.id', $filters['tecido_id']);
            });
        }

        // Filtro por estilista (aceita ID ou nome)
        if (!empty($filters['estilista_id'])) {
            $query->where('estilista_id', $filters['estilista_id']);
        } elseif (!empty($filters['estilista'])) {
            $estilistaId = Estilista::where('nome_estilista', $filters['estilista'])->value('id');
            if ($estilistaId) {
                $query->where('estilista_id', $estilistaId);
            }
        }

        // Filtro por grupo (aceita ID ou nome)
        if (!empty($filters['grupo_id'])) {
            $query->where('grupo_id', $filters['grupo_id']);
        } elseif (!empty($filters['grupo'])) {
            $grupoId = GrupoProduto::where('descricao', $filters['grupo'])->value('id');
            if ($grupoId) {
                $query->where('grupo_id', $grupoId);
            }
        }

        // Filtro por status (aceita ID ou nome)
        if (!empty($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        } elseif (!empty($filters['status'])) {
            $statusId = Status::where('descricao', $filters['status'])->value('id');
            if ($statusId) {
                $query->where('status_id', $statusId);
            }
        }

        // Filtro por localização (aceita ID ou nome)
        $localizacaoId = null;
        
        if (!empty($filters['localizacao_id'])) {
            $localizacaoId = $filters['localizacao_id'];
        } elseif (!empty($filters['localizacao'])) {
            $localizacaoId = \App\Models\Localizacao::where('nome_localizacao', $filters['localizacao'])->value('id');
        }
        
        if ($localizacaoId) {
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
        
        // Filtro por status de conclusão (dropdown)
        $concluido = isset($filters['concluido']) ? $filters['concluido'] : null;
        if ($concluido !== null && $concluido !== '') {
            $concluidoValue = $concluido === '1' ? 1 : 0;
            
            $subquery = \App\Models\Movimentacao::select('produto_id')
                ->where('concluido', $concluidoValue)
                ->whereIn('id', function($q) {
                    $q->select(\DB::raw('MAX(id)'))
                      ->from('movimentacoes')
                      ->groupBy('produto_id');
                });
                
            $query->whereIn('id', $subquery);
        }

        // Incluir excluídos se solicitado
        if (!empty($filters['incluir_excluidos'])) {
            $query->withTrashed();
        }

        // Filtro por data de cadastro (início)
        if (!empty($filters['data_inicio'])) {
            $query->whereDate('created_at', '>=', $filters['data_inicio']);
        }

        // Filtro por data de cadastro (fim)
        if (!empty($filters['data_fim'])) {
            $query->whereDate('created_at', '<=', $filters['data_fim']);
        }

        // Filtro por data prevista de produção (início)
        if (!empty($filters['data_prevista_inicio'])) {
            $query->whereDate('data_prevista_producao', '>=', $filters['data_prevista_inicio']);
        }

        // Filtro por data prevista de produção (fim)
        if (!empty($filters['data_prevista_fim'])) {
            $query->whereDate('data_prevista_producao', '<=', $filters['data_prevista_fim']);
        }

        $produtos = $query->orderBy('referencia')->paginate(10);

        // Buscar dados para os selects de filtro
        $marcas = Marca::orderBy('nome_marca')->get();
        $tecidos = Tecido::orderBy('descricao')->get();
        $estilistas = Estilista::orderBy('nome_estilista')->get();
        $grupos = GrupoProduto::orderBy('descricao')->get();
        $statuses = Status::orderBy('descricao')->get();
        $localizacoes = \App\Models\Localizacao::orderBy('nome_localizacao')->get();
        
        // Preservar os filtros na paginação
        $produtos->appends($filters);

        return view('produtos.index', compact(
            'produtos', 'marcas', 'tecidos', 'estilistas', 'grupos', 
            'statuses', 'localizacoes', 'filters'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->canCreate('produtos')) { abort(403); }
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
        if (!auth()->user()->canCreate('produtos')) { abort(403); }
        // Trim spaces from referencia field if it exists
        if ($request->has('referencia')) {
            $request->merge(['referencia' => trim($request->referencia)]);
        }

        $messages = [
            'referencia.unique' => 'Atenção: Esta referência já está cadastrada no sistema. Por favor, utilize outra referência.',
            'tecidos.required' => 'É necessário adicionar pelo menos um tecido ao produto.',
            'tecidos.min' => 'É necessário adicionar pelo menos um tecido ao produto.',
            'tecidos.*.tecido_id.required' => 'Selecione um tecido válido.',
            'tecidos.*.tecido_id.exists' => 'Um dos tecidos selecionados não existe no sistema.',
            'tecidos.*.consumo.min' => 'O consumo do tecido deve ser maior que zero.',
            'cores.*.cor.required' => 'O nome da cor é obrigatório.',
            'cores.*.quantidade.required' => 'A quantidade da cor é obrigatória.',
            'cores.*.quantidade.min' => 'A quantidade da cor deve ser maior que zero.',
        ];
        
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
        ], $messages);

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

        // Associar as variações de cores ao produto
        if ($request->has('cores')) {
            foreach ($request->cores as $cor) {
                if (!empty($cor['cor']) && !empty($cor['quantidade'])) {
                    $produto->cores()->create([
                        'cor' => $cor['cor'],
                        'codigo_cor' => !empty($cor['codigo_cor']) ? $cor['codigo_cor'] : null,
                        'quantidade' => $cor['quantidade']
                    ]);
                }
            }
        }

        return redirect()->route('produtos.show', $produto->id)
            ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->canRead('produtos')) { abort(403); }
        $produto = Produto::withTrashed()->with(['marca', 'tecidos', 'estilista', 'grupoProduto', 'status'])->findOrFail($id);

        // Carregar as movimentações relacionadas a este produto
        $movimentacoes = \App\Models\Movimentacao::where('produto_id', $id)
            ->with(['localizacao', 'tipo', 'situacao'])
            ->orderBy('data_entrada', 'asc')
            ->get();
            
        // Enriquecer as cores do produto com informações de estoque
        $coresEnriquecidas = collect([]);
        
        foreach ($produto->cores as $cor) {
            $corInfo = [
                'id' => $cor->id,
                'cor' => $cor->cor,
                'codigo_cor' => $cor->codigo_cor,
                'quantidade' => $cor->quantidade,
                'estoque' => 0,
                'necessidade' => 0,
                'saldo' => 0,
                'producao_possivel' => 0,
                'consumo_deste_produto' => 0,
                'consumo_total' => 0
            ];
            
            // Para cada tecido do produto, verificar estoque da cor
            foreach ($produto->tecidos as $tecido) {
                $estoqueCor = \App\Models\TecidoCorEstoque::where('tecido_id', $tecido->id)
                    ->where('cor', $cor->cor)
                    ->first();
                    
                if ($estoqueCor) {
                    $corInfo['estoque'] += $estoqueCor->quantidade;
                    $corInfo['necessidade'] += $estoqueCor->necessidade;
                    $corInfo['saldo'] += $estoqueCor->saldo;
                    
                    // Calcular consumo específico deste produto
                    // Consumo deste produto = quantidade da cor * consumo do tecido
                    $consumoProduto = $cor->quantidade * $tecido->pivot->consumo;
                    $corInfo['consumo_deste_produto'] += $consumoProduto;
                    $corInfo['consumo_total'] += $tecido->pivot->consumo;
                    
                    // Calcular produção possível se houver consumo definido
                    if ($tecido->pivot->consumo > 0) {
                        // Usar o saldo ao invés da quantidade para calcular a produção possível
                        $producaoPossivel = floor($estoqueCor->saldo / $tecido->pivot->consumo);
                        $corInfo['producao_possivel'] += $producaoPossivel;
                    }
                }
            }
            
            $coresEnriquecidas->push($corInfo);
        }

        return view('produtos.show', compact('produto', 'movimentacoes', 'coresEnriquecidas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->canUpdate('produtos')) { abort(403); }
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

        if (!auth()->user()->canUpdate('produtos')) { abort(403); }
        // Trim spaces from referencia field if it exists
        if ($request->has('referencia')) {
            $request->merge(['referencia' => trim($request->referencia)]);
        }

        // Trim spaces from descricao field if it exists
        if ($request->has('descricao')) {
            $request->merge(['descricao' => trim($request->descricao)]);
        }

        $produto = Produto::findOrFail($id);

        // Mensagens de erro personalizadas
        $messages = [
            'referencia.required' => 'A referência do produto é obrigatória.',
            'tecidos.required' => 'É necessário adicionar pelo menos um tecido ao produto.',
            'tecidos.min' => 'É necessário adicionar pelo menos um tecido ao produto.',
            'tecidos.*.tecido_id.required' => 'Selecione um tecido válido.',
            'tecidos.*.tecido_id.exists' => 'Um dos tecidos selecionados não existe no sistema.',
            'tecidos.*.consumo.min' => 'O consumo do tecido deve ser maior que zero.',
        ];

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

        $validator = Validator::make($request->all(), $rules, $messages);

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

        // Atualizar as variações de cores do produto
        if ($request->has('cores')) {
            // Remover todas as cores existentes
            $produto->cores()->delete();
            
            // Adicionar apenas as cores com quantidade > 0
            foreach ($request->cores as $cor) {
                if (!empty($cor['cor']) && isset($cor['quantidade']) && $cor['quantidade'] > 0) {
                    $produto->cores()->create([
                        'cor' => $cor['cor'],
                        'codigo_cor' => !empty($cor['codigo_cor']) ? $cor['codigo_cor'] : null,
                        'quantidade' => $cor['quantidade']
                    ]);
                }
            }
        }


        return redirect()->route('produtos.show', $produto->id)
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->canDelete('produtos')) { abort(403); }
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
    
    /**
     * Gera um PDF do produto
     */
    public function generatePdf(string $id)
    {
        if (!auth()->user()->canRead('produtos')) { abort(403); }
        $produto = Produto::with(['marca', 'grupoProduto', 'status', 'estilista', 'tecidos', 
            'movimentacoes' => function($query) {
                $query->with(['localizacao', 'tipo', 'situacao'])->latest('data_entrada');
            }])->findOrFail($id);
        
        $pdf = PDF::loadView('produtos.pdf', compact('produto'))
               ->setPaper('a4', 'landscape');
        
        return $pdf->stream('produto-' . $produto->referencia . '.pdf');
    }

    /**
     * Obter cores disponíveis dos tecidos selecionados
     */
    public function getAvailableColors(Request $request)
    {
        if (!auth()->user()->canRead('produtos')) { abort(403); }
        $tecidoIds = $request->input('tecido_ids', []);
        $produtoId = $request->input('produto_id');

        // Funções de normalização
        $normalizeCodigo = function ($value) {
            if ($value === null) return null;
            $v = trim((string) $value);
            if ($v === '' || strtolower($v) === 'null' || $v === '-') return null;
            return $v;
        };

        $normalizeCor = function ($value) {
            $v = preg_replace('/\s+/', ' ', trim((string) $value)); // colapsa múltiplos espaços
            return $v;
        };

        $makeKey = function ($cor, $codigo) use ($normalizeCor, $normalizeCodigo) {
            // Comparação case-insensitive para evitar duplicidades por diferença de caixa
            $c = mb_strtoupper($normalizeCor($cor), 'UTF-8');
            $codNorm = $normalizeCodigo($codigo);
            $cod = $codNorm !== null ? mb_strtoupper($codNorm, 'UTF-8') : '';
            return $c . '|' . $cod;
        };

        $coresExistentes = collect([]);
        $coresDisponiveis = collect([]);
        $produto = null;

        // 1. Buscar cores já cadastradas no produto (primeira prioridade)
        if ($produtoId) {
            $produto = \App\Models\Produto::with('tecidos')->find($produtoId);
            if ($produto) {
                $coresExistentes = $produto->cores()->select('cor', 'codigo_cor', 'quantidade')->get()
                    ->map(function($cor) use ($normalizeCor, $normalizeCodigo) {
                        return [
                            'cor' => $normalizeCor($cor->cor),
                            'codigo_cor' => $normalizeCodigo($cor->codigo_cor),
                            'quantidade' => $cor->quantidade,
                            'tipo' => 'existente',
                            'estoque' => 0,
                            'necessidade' => 0,
                            'producao_possivel' => 0
                        ];
                    })
                    // Deduplicar cores existentes após normalização (somando quantidades)
                    ->groupBy(function($c) use ($makeKey) {
                        return $makeKey($c['cor'], $c['codigo_cor']);
                    })
                    ->map(function($group) {
                        $first = $group->first();
                        // Somar quantidades de duplicados
                        $totalQtd = 0;
                        foreach ($group as $g) {
                            $totalQtd += (int) ($g['quantidade'] ?? 0);
                        }
                        $first['quantidade'] = $totalQtd;
                        return $first;
                    })
                    ->values();
            }
        }

        // 2. Buscar cores disponíveis nos tecidos selecionados com informações de estoque
        if (!empty($tecidoIds)) {
            $estoquesCores = \App\Models\TecidoCorEstoque::whereIn('tecido_id', $tecidoIds)
                ->with('tecido')
                ->get()
                ->map(function($estoqueCor) use ($normalizeCor, $normalizeCodigo, $produto) {
                    // Calcular produção possível usando saldo ao invés de quantidade
                    $producaoPossivel = 0;
                    if ($produto && $estoqueCor->tecido) {
                        $tecidoProduto = $produto->tecidos->firstWhere('id', $estoqueCor->tecido_id);
                        if ($tecidoProduto && $tecidoProduto->pivot->consumo > 0) {
                            // Usar saldo ao invés de quantidade para calcular produção possível
                            $producaoPossivel = floor($estoqueCor->saldo / $tecidoProduto->pivot->consumo);
                        }
                    }
                    
                    return [
                        'cor' => $normalizeCor($estoqueCor->cor),
                        'codigo_cor' => $normalizeCodigo($estoqueCor->codigo_cor),
                        'quantidade' => 0,
                        'tipo' => 'disponivel',
                        'estoque' => $estoqueCor->quantidade,
                        'necessidade' => $estoqueCor->necessidade,
                        'saldo' => $estoqueCor->saldo,
                        'producao_possivel' => $producaoPossivel
                    ];
                })
                // Deduplicar após normalização, mantendo o maior estoque para cada cor
                ->groupBy(function($c) use ($makeKey) { 
                    return $makeKey($c['cor'], $c['codigo_cor']); 
                })
                ->map(function($group) {
                    $first = $group->first();
                    
                    // Encontrar o maior estoque e a maior produção possível entre duplicados
                    $maxEstoque = 0;
                    $maxNecessidade = 0;
                    $maxProducaoPossivel = 0;
                    
                    foreach ($group as $g) {
                        $maxEstoque = max($maxEstoque, $g['estoque']);
                        $maxNecessidade = max($maxNecessidade, $g['necessidade']);
                        $maxProducaoPossivel = max($maxProducaoPossivel, $g['producao_possivel']);
                    }
                    
                    $first['estoque'] = $maxEstoque;
                    $first['necessidade'] = $maxNecessidade;
                    $first['producao_possivel'] = $maxProducaoPossivel;
                    
                    return $first;
                })
                ->values();
        }

        // 3. Filtrar cores disponíveis que já não estejam cadastradas no produto
        $coresExistentesKeys = $coresExistentes->map(function($cor) use ($makeKey) {
            return $makeKey($cor['cor'], $cor['codigo_cor']);
        });

        // 4. Atualizar informações de estoque para cores existentes
        if (!empty($tecidoIds) && !$coresExistentes->isEmpty()) {
            $coresExistentes = $coresExistentes->map(function($corExistente) use ($estoquesCores, $makeKey) {
                $key = $makeKey($corExistente['cor'], $corExistente['codigo_cor']);
                
                // Encontrar a mesma cor nas cores disponíveis para obter informações de estoque
                $corDisponivel = $estoquesCores->first(function($c) use ($key, $makeKey) {
                    return $makeKey($c['cor'], $c['codigo_cor']) === $key;
                });
                
                if ($corDisponivel) {
                    $corExistente['estoque'] = $corDisponivel['estoque'];
                    $corExistente['necessidade'] = $corDisponivel['necessidade'];
                    $corExistente['saldo'] = $corDisponivel['saldo'];
                    $corExistente['producao_possivel'] = $corDisponivel['producao_possivel'];
                }
                
                return $corExistente;
            });
        }

        // Adicionar cores disponíveis que não estão no produto
        $coresDisponiveis = $estoquesCores->filter(function($cor) use ($coresExistentesKeys, $makeKey) {
            $key = $makeKey($cor['cor'], $cor['codigo_cor']);
            return !$coresExistentesKeys->contains($key);
        });

        // 5. Combinar: primeiro as existentes, depois as disponíveis
        $todasCores = $coresExistentes->concat($coresDisponiveis)->values();

        return response()->json(['cores' => $todasCores]);
    }

    /**
     * Verificar inconsistências nos produtos
     */
    public function inconsistencias()
    {
        if (!auth()->user()->canRead('produtos')) { abort(403); }
        // Buscar produtos com possíveis inconsistências
        $produtos = Produto::with(['cores', 'marca', 'grupoProduto', 'status'])
            ->whereHas('cores')
            ->get()
            ->filter(function ($produto) {
                $totalCores = $produto->cores->sum('quantidade');
                return $totalCores != $produto->quantidade;
            });

        return view('produtos.inconsistencias', compact('produtos'));
    }

    /**
     * Gerar PDF da lista de produtos
     */
    public function generateListPdf(Request $request)
    {
        if (!auth()->user()->canRead('produtos')) { abort(403); }
        
        // Usar os mesmos filtros da sessão ou da requisição
        $useSessionFilters = !$request->hasAny([
            'referencia', 'descricao', 'marca_id', 'marca', 'tecido_id', 
            'estilista_id', 'estilista', 'grupo_id', 'grupo', 'status_id', 
            'status', 'localizacao_id', 'localizacao', 'incluir_excluidos',
            'data_inicio', 'data_fim', 'data_prevista_inicio', 'data_prevista_fim', 'concluido'
        ]) && $request->method() === 'GET' && !$request->ajax() && !$request->has('force_generate');
        
        $filters = $useSessionFilters ? session('produtos_filters', []) : $request->all();
        
        try {
            // Simplify the query to avoid issues with count and complex selects
            $query = Produto::select('produtos.*'); // Start with a clean select
            
            // Apply filters
            if (!empty($filters['referencia'])) {
                $query->where('referencia', 'like', '%' . $filters['referencia'] . '%');
            }

            if (!empty($filters['descricao'])) {
                $query->where('descricao', 'like', '%' . $filters['descricao'] . '%');
            }

            // Filtro por marca (aceita ID ou nome)
            if (!empty($filters['marca_id'])) {
                $query->where('marca_id', $filters['marca_id']);
            } elseif (!empty($filters['marca'])) {
                $marcaId = Marca::where('nome_marca', $filters['marca'])->value('id');
                if ($marcaId) {
                    $query->where('marca_id', $marcaId);
                }
            }

            if (!empty($filters['tecido_id'])) {
                $query->whereHas('tecidos', function($q) use ($filters) {
                    $q->where('tecidos.id', $filters['tecido_id']);
                });
            }

            // Filtro por estilista (aceita ID ou nome)
            if (!empty($filters['estilista_id'])) {
                $query->where('estilista_id', $filters['estilista_id']);
            } elseif (!empty($filters['estilista'])) {
                $estilistaId = Estilista::where('nome_estilista', $filters['estilista'])->value('id');
                if ($estilistaId) {
                    $query->where('estilista_id', $estilistaId);
                }
            }

            // Filtro por grupo (aceita ID ou nome)
            if (!empty($filters['grupo_id'])) {
                $query->where('grupo_id', $filters['grupo_id']);
            } elseif (!empty($filters['grupo'])) {
                $grupoId = GrupoProduto::where('descricao', $filters['grupo'])->value('id');
                if ($grupoId) {
                    $query->where('grupo_id', $grupoId);
                }
            }

            // Filtro por status (aceita ID ou nome)
            if (!empty($filters['status_id'])) {
                $query->where('status_id', $filters['status_id']);
            } elseif (!empty($filters['status'])) {
                $statusId = Status::where('descricao', $filters['status'])->value('id');
                if ($statusId) {
                    $query->where('status_id', $statusId);
                }
            }

            // Filtro por localização
            $localizacaoId = null;
            
            if (!empty($filters['localizacao_id'])) {
                $localizacaoId = $filters['localizacao_id'];
            } elseif (!empty($filters['localizacao'])) {
                $localizacaoId = \App\Models\Localizacao::where('nome_localizacao', $filters['localizacao'])->value('id');
            }
            
            if ($localizacaoId) {
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
            
            // Filtro por status de conclusão
            $concluido = isset($filters['concluido']) ? $filters['concluido'] : null;
            if ($concluido !== null && $concluido !== '') {
                $concluidoValue = $concluido === '1' ? 1 : 0;
                
                $subquery = \App\Models\Movimentacao::select('produto_id')
                    ->where('concluido', $concluidoValue)
                    ->whereIn('id', function($q) {
                        $q->select(\DB::raw('MAX(id)'))
                          ->from('movimentacoes')
                          ->groupBy('produto_id');
                    });
                    
                $query->whereIn('id', $subquery);
            }

            // Incluir excluídos se solicitado
            if (!empty($filters['incluir_excluidos'])) {
                $query->withTrashed();
            }

            // Filtro por data de cadastro
            if (!empty($filters['data_inicio'])) {
                $query->whereDate('created_at', '>=', $filters['data_inicio']);
            }

            if (!empty($filters['data_fim'])) {
                $query->whereDate('created_at', '<=', $filters['data_fim']);
            }
            
            // Filtro por data prevista de produção
            if (!empty($filters['data_prevista_inicio'])) {
                $query->whereDate('data_prevista_producao', '>=', $filters['data_prevista_inicio']);
            }

            if (!empty($filters['data_prevista_fim'])) {
                $query->whereDate('data_prevista_producao', '<=', $filters['data_prevista_fim']);
            }
            
            // Skip the count check and proceed directly to PDF generation
            // This avoids the error when counting with complex queries
            if ($request->has('force_generate')) {
                // Continue with PDF generation
            } else {
                // Just to be safe, set a reasonable limit
                $query->limit(500);
            }

            // Get the products with all necessary relationships
            $produtos = $query->orderBy('referencia')
                ->with(['marca', 'grupoProduto', 'status', 'estilista'])
                ->get();
                
            // Now that we have the products, we can manually add the localizacao_atual and concluido_atual
            foreach ($produtos as $produto) {
                // Get the latest movimentacao for this product
                $ultimaMovimentacao = \App\Models\Movimentacao::where('produto_id', $produto->id)
                    ->with('localizacao')
                    ->orderBy('id', 'desc')
                    ->first();
                    
                if ($ultimaMovimentacao) {
                    $produto->localizacao_atual = $ultimaMovimentacao->localizacao;
                    $produto->concluido_atual = $ultimaMovimentacao->concluido;
                } else {
                    $produto->localizacao_atual = null;
                    $produto->concluido_atual = null;
                }
            }

            $pdf = PDF::loadView('produtos.lista-pdf', compact('produtos'))
                   ->setPaper('a4', 'landscape');
            
            return $pdf->stream('lista-produtos.pdf');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error generating PDF: ' . $e->getMessage());
            
            // Return a user-friendly error message
            return back()->with('error', 'Ocorreu um erro ao gerar o PDF. Por favor, tente novamente ou entre em contato com o suporte.');
        }
    }
}
