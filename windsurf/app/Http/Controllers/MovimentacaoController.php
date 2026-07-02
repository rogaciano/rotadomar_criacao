<?php

namespace App\Http\Controllers;

use App\Helpers\MovimentacaoHelper;
use App\Http\Controllers\MovimentacaoFilterController;
use App\Models\DirecionamentoComercial;
use App\Models\GrupoProduto;
use App\Models\Localizacao;
use App\Models\Marca;
use App\Models\Movimentacao;
use App\Models\MovimentacaoObservacao;
use App\Models\Produto;
use App\Models\Situacao;
use App\Models\Status;
use App\Models\Tecido;
use App\Models\Tipo;
use App\Services\NotificacaoService;
use App\Http\Requests\StoreMovimentacaoRequest;
use App\Http\Requests\UpdateMovimentacaoRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovimentacaoController extends Controller
{
    /**
     * Exibir movimentações pendentes da localização do usuário logado
     */
    public function minhasMovimentacoes()
    {
        if (!auth()->user() || !auth()->user()->canRead('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        $user = auth()->user();

        // Verificar se o usuário tem uma localização atribuída
        if (!$user->localizacao_id) {
            return view('movimentacoes.minhas', [
                'movimentacoes' => collect(),
                'localizacao' => null,
                'totalPendentes' => 0,
                'totalAtrasadas' => 0,
            ]);
        }

        // Carregar localização do usuário
        $user->load('localizacao');
        $localizacao = $user->localizacao;

        // Obter todas as movimentações pendentes
        $movimentacoes = $user->getMovimentacoesPendentes();

        // Calcular dias e status de atraso para cada movimentação
        $movimentacoes->each(function ($movimentacao) use ($localizacao) {
            // Usar dias úteis ao invés de dias corridos
            $movimentacao->dias_decorridos = MovimentacaoHelper::calcularDiasUteis($movimentacao->data_entrada);

            if ($localizacao->prazo) {
                $movimentacao->esta_atrasado = $movimentacao->dias_decorridos > $localizacao->prazo;
                $movimentacao->dias_restantes = (int) ($localizacao->prazo - $movimentacao->dias_decorridos);
            } else {
                $movimentacao->esta_atrasado = false;
                $movimentacao->dias_restantes = null;
            }
        });

        // Ordenar por dias decorridos (mais atrasadas primeiro)
        $movimentacoes = $movimentacoes->sortByDesc('dias_decorridos');

        // Contar totais
        $totalPendentes = $movimentacoes->count();
        $totalAtrasadas = $movimentacoes->where('esta_atrasado', true)->count();

        return view('movimentacoes.minhas', compact('movimentacoes', 'localizacao', 'totalPendentes', 'totalAtrasadas'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->canRead('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        // Verificar se é uma requisição de limpeza de filtros
        if ($request->has('limpar_filtros')) {
            auth()->user()->clearFilters('movimentacoes');
            return redirect()->route('movimentacoes.filtro.status-dias');
        }

        // Se tem parâmetros de filtro na URL, salvar como filtros do usuário
        if ($request->anyFilled([
            'referencia', 'produto', 'produto_id', 'marca_id', 'status_id', 'tecido_id',
            'tipo_id', 'situacao_id', 'localizacao_id', 'data_inicio', 'data_fim',
            'comprometido', 'concluido', 'sort', 'direction', 'status_dias', 'grupo_produto_id', 'direcionamento_comercial_id'
        ])) {
            $filterParams = $request->only([
                'referencia', 'produto', 'produto_id', 'marca_id', 'status_id', 'tecido_id',
                'tipo_id', 'situacao_id', 'localizacao_id', 'data_inicio', 'data_fim',
                'comprometido', 'concluido', 'sort', 'direction', 'status_dias', 'grupo_produto_id', 'direcionamento_comercial_id'
            ]);

            auth()->user()->saveFilters('movimentacoes', $filterParams);
        }
        // Se não tem parâmetros na URL mas tem filtros salvos, redirecionar com os filtros salvos
        else if (!$request->hasAny([
            'referencia', 'produto', 'produto_id', 'marca_id', 'status_id', 'tecido_id',
            'tipo_id', 'situacao_id', 'localizacao_id', 'data_inicio', 'data_fim',
            'comprometido', 'concluido', 'sort', 'direction', 'status_dias', 'grupo_produto_id', 'direcionamento_comercial_id'
        ])) {
            $savedFilters = auth()->user()->getFilters('movimentacoes');

            if (!empty($savedFilters)) {
                return redirect()->route('movimentacoes.filtro.status-dias', $savedFilters);
            }
        }

        $query = Movimentacao::with([
            'produto',
            'produto.marca',
            'produto.direcionamentoComercial',
            'tipo',
            'situacao',
            'localizacao'
        ]);

        // Restrição para usuários de localização: só veem suas localizações permitidas
        $user = auth()->user();
        if ($user->isUsuarioLocalizacao()) {
            $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
            if (!empty($localizacoesPermitidas)) {
                $query->whereIn('localizacao_id', $localizacoesPermitidas);
            }
        }

        // Filtro por referência do produto
        if ($request->filled('referencia')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('referencia', 'like', '%' . $request->referencia . '%');
            });
        }

        // Filtro por descrição do produto
        if ($request->filled('produto')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->produto . '%');
            });
        }

        // Filtro por ID do produto
        if ($request->filled('produto_id')) {
            $query->where('produto_id', $request->produto_id);
        }

        if ($request->filled('marca_id')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('marca_id', $request->marca_id);
            });
        }

        // Filtro por Grupo de Produto
        if ($request->filled('grupo_produto_id')) {
            $grupoProdutoIds = is_array($request->grupo_produto_id) ? $request->grupo_produto_id : [$request->grupo_produto_id];
            $query->whereHas('produto', function($q) use ($grupoProdutoIds) {
                $q->whereIn('grupo_id', $grupoProdutoIds);
            });
        }

        // Filtro por Status do produto
        if ($request->filled('status_id')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('status_id', $request->status_id);
            });
        }

        // Filtro por Tecido do produto
        if ($request->filled('tecido_id')) {
            $tecidoIds = is_array($request->tecido_id) ? $request->tecido_id : [$request->tecido_id];
            $query->whereHas('produto', function($q) use ($tecidoIds) {
                $q->whereHas('tecidos', function($tq) use ($tecidoIds) {
                    $tq->whereIn('tecidos.id', $tecidoIds);
                });
            });
        }

        // Filtro por Direcionamento Comercial do produto
        if ($request->filled('direcionamento_comercial_id')) {
            $direcionamentoIds = is_array($request->direcionamento_comercial_id) ? $request->direcionamento_comercial_id : [$request->direcionamento_comercial_id];
            $query->whereHas('produto', function($q) use ($direcionamentoIds) {
                $q->whereIn('direcionamento_comercial_id', $direcionamentoIds);
            });
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->filled('situacao_id')) {
            $situacaoIds = is_array($request->situacao_id) ? $request->situacao_id : [$request->situacao_id];
            $query->whereIn('situacao_id', $situacaoIds);
        }

        if ($request->filled('localizacao_id')) {
            $localizacaoIds = is_array($request->localizacao_id) ? $request->localizacao_id : [$request->localizacao_id];
            $query->whereIn('localizacao_id', $localizacaoIds);
        }

        // Filtros de data
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

        // Adicionar filtro para o campo concluido
        if ($request->filled('concluido')) {
            $query->where('concluido', $request->concluido);
        }

        // Filtro por status de dias (Atrasados, Em Dia)
        if ($request->filled('status_dias')) {
            $query = MovimentacaoFilterController::applyStatusDiasFilter($query, $request->status_dias);
        }

        // Ordenação
        if ($request->filled('sort') && $request->filled('direction')) {
            $sortField = $request->sort;
            $direction = in_array(strtolower($request->direction), ['asc', 'desc']) ? $request->direction : 'asc';

            // Mapear os campos de ordenação para as colunas corretas no banco de dados
            switch ($sortField) {
                case 'produto':
                    $query->join('produtos', 'movimentacoes.produto_id', '=', 'produtos.id')
                          ->orderBy('produtos.descricao', $direction)
                          ->select('movimentacoes.*'); // Evitar ambiguidade de colunas
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
                case 'localizacao':
                    $query->join('localizacoes', 'movimentacoes.localizacao_id', '=', 'localizacoes.id')
                          ->orderBy('localizacoes.nome_localizacao', $direction)
                          ->select('movimentacoes.*');
                    break;
                default:
                    // Para campos diretos da tabela movimentacoes
                    if (in_array($sortField, ['data_entrada', 'data_saida', 'data_devolucao', 'comprometido', 'observacao', 'created_at', 'concluido'])) {
                        $query->orderBy($sortField, $direction);
                    } else {
                        // Ordenação padrão se o campo não for reconhecido
                        $query->orderBy('created_at', 'desc');
                    }
                    break;
            }
        } else {
            // Ordenação padrão
            $query->orderBy('created_at', 'desc');
        }

        $movimentacoes = $query->paginate(15)->withQueryString();

        // Carregar produtos para o select
        $produtos = Produto::orderBy('referencia')->get();

        // Carregar situações para o select
        $situacoesAtivas = Situacao::where('ativo', true)->orderBy('descricao')->get();
        $situacoesInativas = Situacao::where('ativo', false)->orderBy('descricao')->get();

        // Combinar as coleções: ativas primeiro, depois inativas
        $situacoes = $situacoesAtivas->concat($situacoesInativas);

        // Carregar tipos para o select
        $tiposAtivos = Tipo::where('ativo', true)->orderBy('descricao')->get();
        $tiposInativos = Tipo::where('ativo', false)->orderBy('descricao')->get();

        // Combinar as coleções: ativas primeiro, depois inativas
        $tipos = $tiposAtivos->concat($tiposInativos);

        // Carregar localizações para o select
        $localizacoesAtivas = Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get();
        $localizacoesInativas = Localizacao::where('ativo', false)->orderBy('nome_localizacao')->get();

        // Combinar as coleções: ativas primeiro, depois inativas
        $localizacoes = $localizacoesAtivas->concat($localizacoesInativas);

        $status = Status::where('ativo', true)->orderBy('descricao')->get();

        // Carregar marcas para o filtro
        $marcas = Marca::where('ativo', true)->orderBy('nome_marca')->get();

        // Carregar tecidos para o filtro
        $tecidos = Tecido::where('ativo', true)->orderBy('descricao')->get();

        // Carregar grupos de produtos para o filtro (incluindo ativo = NULL)
        $grupoProdutos = GrupoProduto::orderBy('descricao')->get();

        // Carregar direcionamentos comerciais para o filtro
        $direcionamentosComerciais = DirecionamentoComercial::where('ativo', true)->orderBy('descricao')->get();

        return view('movimentacoes.index', compact('movimentacoes', 'produtos', 'situacoes', 'tipos', 'localizacoes', 'status', 'marcas', 'tecidos', 'grupoProdutos', 'direcionamentosComerciais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!auth()->user() || !auth()->user()->canCreate('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        $produtos = Produto::orderBy('referencia')->get();
        $situacoes = Situacao::where('ativo', true)->orderBy('descricao')->get();
        $tipos = Tipo::where('ativo', true)->orderBy('descricao')->get();
        $localizacoes = Localizacao::where('ativo', true)
            ->where('faz_movimentacao', true)
            ->orderBy('nome_localizacao')
            ->get();

        // Pré-selecionar produto se fornecido via query string
        $produto_id = $request->query('produto_id');
        $produto_selecionado = null;

        if ($produto_id) {
            $produto_selecionado = Produto::find($produto_id);
        }

        return view('movimentacoes.create', compact('produtos', 'situacoes', 'tipos', 'localizacoes', 'produto_id', 'produto_selecionado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovimentacaoRequest $request)
    {
        $validated = $request->validated();

        // Validação customizada: não permitir concluído sem data de devolução
        if ($request->has('concluido') && empty($validated['data_devolucao'])) {
            return back()->withInput()->with('error', 'Para marcar como concluído, é necessário preencher a Data de Devolução.');
        }

        // Verificar se o produto pode ter movimentações
        $produto = Produto::findOrFail($validated['produto_id']);
        if (!$produto->podeMovimentar()) {
            return back()->withInput()->with('error', 'Este produto não pode ter movimentações. Contate o administrador.');
        }

        try {
            $movimentacao = new Movimentacao();
            $movimentacao->produto_id = $validated['produto_id'];
            $movimentacao->tipo_id = $validated['tipo_id'];
            $movimentacao->situacao_id = $validated['situacao_id'];
            $movimentacao->localizacao_id = $validated['localizacao_id'];
            $movimentacao->data_entrada = $validated['data_entrada'];
            $movimentacao->data_saida = $validated['data_saida'] ?? null;
            $movimentacao->data_devolucao = $validated['data_devolucao'] ?? null;
            $movimentacao->comprometido = 0; // Valor padrão
            $movimentacao->created_by = auth()->id(); // Salvar o usuário que criou

            $movimentacao->concluido = $request->has('concluido');

            // Upload de anexo se existir
            if ($request->hasFile('anexo')) {
                $anexo = $request->file('anexo');
                $nomeArquivo = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $anexo->getClientOriginalName());
                $anexo->move(public_path('uploads/movimentacoes'), $nomeArquivo);
                $movimentacao->anexo = $nomeArquivo;
            }

            $movimentacao->save();

            // Salvar observação na tabela movimentacoes_observacoes
            if (!empty($validated['observacao'])) {
                $movimentacao->observacoes()->create([
                    'observacao' => $validated['observacao']
                ]);
            }

            // Criar notificação para nova movimentação
            $notificacaoService = new NotificacaoService();
            $notificacaoService->criarNotificacaoNovaMovimentacao($movimentacao);

            // Verificar se deve redirecionar de volta para o show do produto
            if ($request->has('redirect_to_produto') && $request->redirect_to_produto) {
                return redirect()->route('produtos.show', $request->redirect_to_produto)
                        ->with('success', 'Movimentação criada com sucesso!');
            }

            // Usar os filtros salvos do usuário
            $savedFilters = auth()->user()->getFilters('movimentacoes');

            return redirect()->route('movimentacoes.filtro.status-dias', $savedFilters)
                    ->with('success', 'Movimentação criada com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar movimentação: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Movimentacao $movimentacao)
    {
        if (!auth()->user() || !auth()->user()->canRead('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        $backUrl = $request->query('back_url');

        $movimentacao->load([
            'produto', 'localizacao', 'tipo', 'situacao',
            'observacoes' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ]);

        // Buscar histórico de atividades desta movimentação
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', Movimentacao::class)
            ->where('subject_id', $movimentacao->id)
            ->with('causer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('movimentacoes.show', compact('movimentacao', 'backUrl', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Movimentacao $movimentacao)
    {
        if (!auth()->user() || !auth()->user()->canUpdate('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        $movimentacao->load(['produto', 'localizacao', 'tipo', 'situacao']);

        $produtos = Produto::orderBy('referencia')->get();
        $situacoes = Situacao::orderBy('descricao')->get();
        $tipos = Tipo::orderBy('descricao')->get();
        $localizacoes = Localizacao::where('faz_movimentacao', true)
            ->orderBy('nome_localizacao')
            ->get();
        $backUrl = $request->query('back_url');

        return view('movimentacoes.edit', compact('movimentacao', 'produtos', 'situacoes', 'tipos', 'localizacoes', 'backUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovimentacaoRequest $request, Movimentacao $movimentacao)
    {
        $validated = $request->validated();

        // Validação customizada: não permitir concluído sem data de devolução
        if ($request->has('concluido') && empty($validated['data_devolucao'])) {
            return back()->withInput()->with('error', 'Para marcar como concluído, é necessário preencher a Data de Devolução.');
        }

        // Verificar se o produto pode ter movimentações
        $produto = Produto::findOrFail($validated['produto_id']);
        if (!$produto->podeMovimentar()) {
            return back()->withInput()->with('error', 'Este produto não pode ter movimentações. Contate o administrador.');
        }

        // Upload de anexo se existir
        if ($request->hasFile('anexo')) {
            // Remover anexo antigo se existir
            if ($movimentacao->anexo) {
                $caminhoAnexoAntigo = public_path('uploads/movimentacoes/' . $movimentacao->anexo);
                if (file_exists($caminhoAnexoAntigo)) {
                    unlink($caminhoAnexoAntigo);
                }
            }

            $anexo = $request->file('anexo');
            $nomeArquivo = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $anexo->getClientOriginalName());
            $anexo->move(public_path('uploads/movimentacoes'), $nomeArquivo);
            $validated['anexo'] = $nomeArquivo;
        } else {
            // Manter o anexo existente
            unset($validated['anexo']);
        }

        // Tratar checkbox concluido (quando não marcado, não vem no request)
        $concluidoAntes = $movimentacao->concluido;
        $validated['concluido'] = $request->has('concluido');

        // Remover observacao do update direto - observações são gerenciadas pela tabela movimentacoes_observacoes
        unset($validated['observacao']);

        $movimentacao->update($validated);

        // Criar notificação se movimentação foi marcada como concluída
        if (!$concluidoAntes && $validated['concluido']) {
            $notificacaoService = new NotificacaoService();
            $notificacaoService->criarNotificacaoMovimentacaoConcluida($movimentacao);
        }

        // Verificar se existe back_url para retornar à página de origem
        if ($request->has('back_url') && $request->back_url && Str::startsWith($request->back_url, ['/', url('/')])) {
            return redirect($request->back_url)
                    ->with('success', 'Movimentação atualizada com sucesso!');
        }

        // Usar os filtros salvos do usuário
        $savedFilters = auth()->user()->getFilters('movimentacoes');

        return redirect()->route('movimentacoes.filtro.status-dias', $savedFilters)
                ->with('success', 'Movimentação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimentacao $movimentacao)
    {
        if (!auth()->user() || !auth()->user()->canDelete('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        try {
            // Remover anexo se existir
            if ($movimentacao->anexo) {
                $caminhoAnexo = public_path('uploads/movimentacoes/' . $movimentacao->anexo);
                if (file_exists($caminhoAnexo)) {
                    unlink($caminhoAnexo);
                }
            }

            $movimentacao->delete();

            // Usar os filtros salvos do usuário
            $savedFilters = auth()->user()->getFilters('movimentacoes');

            return redirect()->route('movimentacoes.filtro.status-dias', $savedFilters)
                    ->with('success', 'Movimentação excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir movimentação: ' . $e->getMessage());
        }
    }

    /**
     * Gerar PDF da movimentação
     */
    public function generatePdf(Movimentacao $movimentacao)
    {
        if (!auth()->user() || !auth()->user()->canRead('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        $movimentacao->load(['produto', 'localizacao', 'tipo', 'situacao']);

        $pdf = PDF::loadView('movimentacoes.pdf', compact('movimentacao'));
        return $pdf->stream('movimentacao_' . $movimentacao->id . '.pdf');
    }

    /**
     * Gerar PDF da lista de movimentações filtradas
     */
    public function generateListPdf(Request $request)
    {
        if (!auth()->user() || !auth()->user()->canRead('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        $query = Movimentacao::with(['produto', 'produto.marca', 'tipo', 'situacao', 'localizacao']);

        // Aplicar os mesmos filtros da listagem
        if ($request->filled('referencia')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('referencia', 'like', '%' . $request->referencia . '%');
            });
        }

        if ($request->filled('produto')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->produto . '%');
            });
        }

        if ($request->filled('produto_id')) {
            $query->where('produto_id', $request->produto_id);
        }

        if ($request->filled('marca_id')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('marca_id', $request->marca_id);
            });
        }

        // Filtro por Grupo de Produto
        if ($request->filled('grupo_produto_id')) {
            $grupoProdutoIds = is_array($request->grupo_produto_id) ? $request->grupo_produto_id : [$request->grupo_produto_id];
            $query->whereHas('produto', function($q) use ($grupoProdutoIds) {
                $q->whereIn('grupo_id', $grupoProdutoIds);
            });
        }

        // Filtro por Status do produto
        if ($request->filled('status_id')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('status_id', $request->status_id);
            });
        }

        // Filtro por Tecido do produto
        if ($request->filled('tecido_id')) {
            $tecidoIds = is_array($request->tecido_id) ? $request->tecido_id : [$request->tecido_id];
            $query->whereHas('produto', function($q) use ($tecidoIds) {
                $q->whereHas('tecidos', function($tq) use ($tecidoIds) {
                    $tq->whereIn('tecidos.id', $tecidoIds);
                });
            });
        }

        // Filtro por Direcionamento Comercial do produto
        if ($request->filled('direcionamento_comercial_id')) {
            $direcionamentoIds = is_array($request->direcionamento_comercial_id) ? $request->direcionamento_comercial_id : [$request->direcionamento_comercial_id];
            $query->whereHas('produto', function($q) use ($direcionamentoIds) {
                $q->whereIn('direcionamento_comercial_id', $direcionamentoIds);
            });
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->filled('situacao_id')) {
            $situacaoIds = is_array($request->situacao_id) ? $request->situacao_id : [$request->situacao_id];
            $query->whereIn('situacao_id', $situacaoIds);
        }

        if ($request->filled('localizacao_id')) {
            $localizacaoIds = is_array($request->localizacao_id) ? $request->localizacao_id : [$request->localizacao_id];
            $query->whereIn('localizacao_id', $localizacaoIds);
        }

        // Filtros de data
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

        // Adicionar filtro para o campo concluido
        if ($request->filled('concluido')) {
            $query->where('concluido', $request->concluido);
        }

        // Filtro por status de dias (Atrasados, Em Dia)
        if ($request->filled('status_dias')) {
            $query = MovimentacaoFilterController::applyStatusDiasFilter($query, $request->status_dias);
        }

        // Ordenação
        if ($request->filled('sort') && $request->filled('direction')) {
            $sortField = $request->sort;
            $direction = in_array(strtolower($request->direction), ['asc', 'desc']) ? $request->direction : 'asc';

            // Mapear os campos de ordenação para as colunas corretas no banco de dados
            switch ($sortField) {
                case 'produto':
                    $query->join('produtos', 'movimentacoes.produto_id', '=', 'produtos.id')
                          ->orderBy('produtos.descricao', $direction)
                          ->select('movimentacoes.*'); // Evitar ambiguidade de colunas
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
                case 'localizacao':
                    $query->join('localizacoes', 'movimentacoes.localizacao_id', '=', 'localizacoes.id')
                          ->orderBy('localizacoes.nome_localizacao', $direction)
                          ->select('movimentacoes.*');
                    break;
                default:
                    // Para campos diretos da tabela movimentacoes
                    if (in_array($sortField, ['data_entrada', 'data_saida', 'data_devolucao', 'comprometido', 'observacao', 'created_at', 'concluido'])) {
                        $query->orderBy($sortField, $direction);
                    } else {
                        // Ordenação padrão se o campo não for reconhecido
                        $query->orderBy('created_at', 'desc');
                    }
                    break;
            }
        } else {
            // Ordenação padrão
            $query->orderBy('created_at', 'desc');
        }

        $movimentacoes = $query->get();

        $pdf = PDF::loadView('movimentacoes.pdf_lista', compact('movimentacoes', 'request'));
        return $pdf->stream('lista_movimentacoes.pdf');
    }

    /**
     * Remover anexo da movimentação
     */
    public function removerAnexo(Movimentacao $movimentacao)
    {
        if (!auth()->user() || !auth()->user()->canUpdate('movimentacoes')) {
            abort(403, 'Acesso negado.');
        }

        if ($movimentacao->anexo) {
            $caminhoAnexo = public_path('uploads/movimentacoes/' . $movimentacao->anexo);
            if (file_exists($caminhoAnexo)) {
                unlink($caminhoAnexo);
            }

            $movimentacao->anexo = null;
            $movimentacao->save();

            // Se estamos na página de edição, voltar para lá
            if (request()->is('*/edit') || request()->is('*/edit/*')) {
                return back()->with('success', 'Anexo removido com sucesso!');
            } else {
                // Usar os filtros salvos do usuário
                $savedFilters = auth()->user()->getFilters('movimentacoes');

                return redirect()->route('movimentacoes.filtro.status-dias', $savedFilters)
                        ->with('success', 'Anexo removido com sucesso!');
            }
        }

        return back()->with('error', 'Nenhum anexo encontrado para remover.');
    }

    /**
     * Adicionar nova observação à movimentação
     */
    public function storeObservacao(Request $request, Movimentacao $movimentacao)
    {
        if (!auth()->user() || !auth()->user()->canCreate('movimentacoes_observacoes')) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'observacao' => 'required|string|max:5000'
        ]);

        $observacao = $movimentacao->observacoes()->create([
            'observacao' => $request->observacao
        ]);

        activity()
            ->performedOn($movimentacao)
            ->causedBy(auth()->user())
            ->event('observacao_criada')
            ->withProperties([
                'observacao_id' => $observacao->id,
                'observacao' => $observacao->observacao,
            ])
            ->log('Observação adicionada');

        return response()->json([
            'success' => true,
            'message' => 'Observação adicionada com sucesso!',
            'observacoes' => $movimentacao->fresh()->observacao,
            'observacao' => [
                'id' => $observacao->id,
                'texto' => $observacao->observacao,
                'created_at' => $observacao->created_at->format('d/m/Y H:i')
            ]
        ]);
    }

    /**
     * Atualizar uma observação existente
     */
    public function updateObservacao(Request $request, MovimentacaoObservacao $observacao)
    {
        if (!auth()->user() || !auth()->user()->canUpdate('movimentacoes_observacoes')) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'observacao' => 'required|string|max:5000'
        ]);

        $textoAnterior = $observacao->observacao;
        $observacao->update([
            'observacao' => $request->observacao
        ]);

        $movimentacao = $observacao->movimentacao;
        if ($movimentacao) {
            activity()
                ->performedOn($movimentacao)
                ->causedBy(auth()->user())
                ->event('observacao_atualizada')
                ->withProperties([
                    'observacao_id' => $observacao->id,
                    'observacao_antiga' => $textoAnterior,
                    'observacao_nova' => $observacao->observacao,
                ])
                ->log('Observação atualizada');
        }

        return response()->json([
            'success' => true,
            'message' => 'Observação atualizada com sucesso!',
            'observacao' => [
                'id' => $observacao->id,
                'texto' => $observacao->observacao,
                'updated_at' => $observacao->updated_at->format('d/m/Y H:i')
            ]
        ]);
    }

    /**
     * Excluir uma observação
     */
    public function destroyObservacao(MovimentacaoObservacao $observacao)
    {
        if (!auth()->user() || !auth()->user()->canDelete('movimentacoes_observacoes')) {
            abort(403, 'Acesso negado.');
        }

        $textoObservacao = $observacao->observacao;
        $movimentacao = $observacao->movimentacao;

        $observacao->delete();

        if ($movimentacao) {
            activity()
                ->performedOn($movimentacao)
                ->causedBy(auth()->user())
                ->event('observacao_excluida')
                ->withProperties([
                    'observacao_id' => $observacao->id,
                    'observacao' => $textoObservacao,
                ])
                ->log('Observação excluída');
        }

        return response()->json([
            'success' => true,
            'message' => 'Observação excluída com sucesso!'
        ]);
    }
}
