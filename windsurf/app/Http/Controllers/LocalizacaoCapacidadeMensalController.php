<?php

namespace App\Http\Controllers;

use App\Models\LocalizacaoCapacidadeMensal;
use App\Models\Localizacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalizacaoCapacidadeMensalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LocalizacaoCapacidadeMensal::with('localizacao');

        // Filtros
        if ($request->filled('localizacao_id')) {
            $query->where('localizacao_id', $request->localizacao_id);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }

        $capacidades = $query->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->orderBy('localizacao_id')
            ->paginate(15);

        // Carregar localizações para o filtro
        $localizacoes = Localizacao::where('ativo', true)
            ->orderBy('nome_localizacao')
            ->get();

        return view('localizacao-capacidade.index', compact('capacidades', 'localizacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $localizacoes = Localizacao::where('ativo', true)
            ->orderBy('nome_localizacao')
            ->get();

        return view('localizacao-capacidade.create', compact('localizacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'localizacao_id' => 'required|exists:localizacoes,id',
            'mes' => 'required|integer|min:1|max:12',
            'ano' => 'required|integer|min:2020|max:2100',
            'capacidade' => 'required|integer|min:0',
            'observacoes' => 'nullable|string'
        ], [
            'localizacao_id.required' => 'A localização é obrigatória.',
            'localizacao_id.exists' => 'Localização inválida.',
            'mes.required' => 'O mês é obrigatório.',
            'mes.min' => 'O mês deve ser entre 1 e 12.',
            'mes.max' => 'O mês deve ser entre 1 e 12.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.min' => 'Ano inválido.',
            'capacidade.required' => 'A capacidade é obrigatória.',
            'capacidade.min' => 'A capacidade deve ser maior ou igual a 0.'
        ]);

        if ($validator->fails()) {
            return redirect()->route('localizacao-capacidade.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar se já existe capacidade (inclusive soft-deleted) para esta localização/mês/ano
        $existente = LocalizacaoCapacidadeMensal::withTrashed()
            ->where('localizacao_id', $request->localizacao_id)
            ->where('mes', $request->mes)
            ->where('ano', $request->ano)
            ->first();

        $data = $request->only(['localizacao_id', 'mes', 'ano', 'capacidade', 'observacoes']);

        if (empty(trim($data['observacoes'] ?? ''))) {
            $data['observacoes'] = null;
        }

        if ($existente) {
            // Se já existe e está soft-deleted, restaurar e atualizar
            if ($existente->trashed()) {
                $existente->restore();
                $existente->update($data);

                return redirect()->route('localizacao-capacidade.index')
                    ->with('success', 'Capacidade mensal restaurada e atualizada com sucesso!');
            }

            // Se já existe ativo, manter a validação atual de não duplicar
            return redirect()->route('localizacao-capacidade.create')
                ->withErrors(['mes' => 'Já existe uma capacidade cadastrada para esta localização neste mês/ano.'])
                ->withInput();
        }

        LocalizacaoCapacidadeMensal::create($data);

        return redirect()->route('localizacao-capacidade.index')
            ->with('success', 'Capacidade mensal criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $capacidade = LocalizacaoCapacidadeMensal::with('localizacao')->findOrFail($id);

        // Buscar produtos diretamente pela data_prevista_faccao
        $produtos = \App\Models\Produto::whereHas('localizacoes', function($query) use ($capacidade) {
            $query->where('localizacao_id', $capacidade->localizacao_id)
                  ->whereMonth('data_prevista_faccao', $capacidade->mes)
                  ->whereYear('data_prevista_faccao', $capacidade->ano);
        })
        ->with(['marca', 'grupoProduto', 'observacoes', 'direcionamentoComercial', 'localizacoes' => function($query) use ($capacidade) {
            $query->where('localizacao_id', $capacidade->localizacao_id)
                  ->whereMonth('data_prevista_faccao', $capacidade->mes)
                  ->whereYear('data_prevista_faccao', $capacidade->ano);
        }])
        ->get()
        ->map(function($produto) {
            // Adicionar quantidade do pivot para compatibilidade com a view
            $produto->quantidade = $produto->localizacoes->sum('pivot.quantidade');
            return $produto;
        });

        return view('localizacao-capacidade.show', compact('capacidade', 'produtos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $capacidade = LocalizacaoCapacidadeMensal::findOrFail($id);

        $localizacoes = Localizacao::where('ativo', true)
            ->orderBy('nome_localizacao')
            ->get();

        return view('localizacao-capacidade.edit', compact('capacidade', 'localizacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $capacidade = LocalizacaoCapacidadeMensal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'localizacao_id' => 'required|exists:localizacoes,id',
            'mes' => 'required|integer|min:1|max:12',
            'ano' => 'required|integer|min:2020|max:2100',
            'capacidade' => 'required|integer|min:0',
            'observacoes' => 'nullable|string'
        ], [
            'localizacao_id.required' => 'A localização é obrigatória.',
            'localizacao_id.exists' => 'Localização inválida.',
            'mes.required' => 'O mês é obrigatório.',
            'mes.min' => 'O mês deve ser entre 1 e 12.',
            'mes.max' => 'O mês deve ser entre 1 e 12.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.min' => 'Ano inválido.',
            'capacidade.required' => 'A capacidade é obrigatória.',
            'capacidade.min' => 'A capacidade deve ser maior ou igual a 0.'
        ]);

        if ($validator->fails()) {
            return redirect()->route('localizacao-capacidade.edit', $capacidade)
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar se já existe outra capacidade para esta localização/mês/ano
        $existente = LocalizacaoCapacidadeMensal::where('localizacao_id', $request->localizacao_id)
            ->where('mes', $request->mes)
            ->where('ano', $request->ano)
            ->where('id', '!=', $id)
            ->first();

        if ($existente) {
            return redirect()->route('localizacao-capacidade.edit', $capacidade)
                ->withErrors(['mes' => 'Já existe uma capacidade cadastrada para esta localização neste mês/ano.'])
                ->withInput();
        }

        $data = $request->only(['localizacao_id', 'mes', 'ano', 'capacidade', 'observacoes']);

        if (empty(trim($data['observacoes'] ?? ''))) {
            $data['observacoes'] = null;
        }

        $capacidade->update($data);

        return redirect()->route('localizacao-capacidade.index')
            ->with('success', 'Capacidade mensal atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $capacidade = LocalizacaoCapacidadeMensal::withTrashed()->findOrFail($id);

        if ($capacidade->trashed()) {
            // Restaurar
            $capacidade->restore();
            $message = 'Capacidade mensal restaurada com sucesso!';
        } else {
            // Excluir
            $capacidade->delete();
            $message = 'Capacidade mensal excluída com sucesso!';
        }

        return redirect()->route('localizacao-capacidade.index')
            ->with('success', $message);
    }

    /**
     * Dashboard de capacidade das localizações
     */
    public function dashboard(Request $request)
    {
        // Mês e ano padrão (atual)
        $mes = $request->filled('mes') ? $request->mes : now()->month;
        $ano = $request->filled('ano') ? $request->ano : now()->year;
        $localizacaoId = $request->filled('localizacao_id') ? $request->localizacao_id : null;
        $etapaId = $request->filled('etapa_id') ? $request->etapa_id : null;
        $marcaId = $request->filled('marca_id') ? $request->marca_id : null;
        $referencia = $request->input('referencia');

        // Verificar se o usuário é um usuário de localização (facção/setor)
        $user = auth()->user();
        $usuarioRestrito = false;
        $localizacoesPermitidas = [];

        if ($user->isUsuarioLocalizacao()) {
            // Usuário de localização: pode ver sua localização principal + localizações de visualização
            $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
            $usuarioRestrito = true;

            // Se não há filtro, usar a localização principal por padrão
            if (!$localizacaoId) {
                $localizacaoId = $user->localizacao_id;
            } elseif (!in_array($localizacaoId, $localizacoesPermitidas)) {
                // Se há filtro inválido, voltar para a principal
                $localizacaoId = $user->localizacao_id;
            }
        }

        // Buscar capacidades do período
        $query = LocalizacaoCapacidadeMensal::with('localizacao')
            ->where('mes', $mes)
            ->where('ano', $ano);

        // Aplicar filtro de localização
        if ($localizacaoId) {
            $query->where('localizacao_id', $localizacaoId);
        } elseif ($usuarioRestrito && !empty($localizacoesPermitidas)) {
            // Se usuário restrito sem filtro, mostrar TODAS suas localizações permitidas
            $query->whereIn('localizacao_id', $localizacoesPermitidas);
        }

        $capacidades = $query->get();

        // Mapear dados para o formato esperado pela view
        $dadosDashboard = $capacidades->map(function ($capacidade) use ($mes, $ano, $etapaId, $marcaId, $referencia) {
            // Buscar produtos diretamente pela data_prevista_faccao em produto_localizacao
            $produtosQuery = \App\Models\Produto::whereHas('localizacoes', function($query) use ($capacidade, $mes, $ano, $etapaId) {
                $query->where('localizacao_id', $capacidade->localizacao_id)
                      ->whereMonth('data_prevista_faccao', $mes)
                      ->whereYear('data_prevista_faccao', $ano);

                // Filtrar por etapa se selecionada
                if ($etapaId) {
                    $query->where('etapa_atual_id', $etapaId);
                }
            })
            ->with(['marca', 'grupoProduto', 'status', 'observacoes', 'direcionamentoComercial', 'localizacoes' => function($query) use ($capacidade, $mes, $ano, $etapaId) {
                $query->where('localizacao_id', $capacidade->localizacao_id)
                      ->whereMonth('data_prevista_faccao', $mes)
                      ->whereYear('data_prevista_faccao', $ano);

                // Filtrar por etapa se selecionada
                if ($etapaId) {
                    $query->where('etapa_atual_id', $etapaId);
                }
            }]);

            // Filtrar por marca se selecionada
            if ($marcaId) {
                $produtosQuery->where('marca_id', $marcaId);
            }

            // Filtrar por referência se informada
            if ($referencia) {
                $produtosQuery->where('referencia', 'like', "%{$referencia}%");
            }

            $produtos = $produtosQuery->get()
            ->map(function($produto) {
                $produto->quantidade_alocada = $produto->localizacoes->sum('pivot.quantidade');
                return $produto;
            });

            $produtosPrevistos = $produtos->sum('quantidade_alocada');

            return [
                'localizacao' => $capacidade->localizacao,
                'capacidade' => $capacidade->capacidade,
                'observacoes' => $capacidade->observacoes,
                'produtos_previstos' => $produtosPrevistos,
                'produtos' => $produtos,
                'saldo' => $capacidade->capacidade - $produtosPrevistos,
                'percentual' => $capacidade->capacidade > 0 ? round(($produtosPrevistos / $capacidade->capacidade) * 100, 1) : 0,
                'acima_capacidade' => $produtosPrevistos > $capacidade->capacidade
            ];
        });

        // Localizações para filtro (restrito para usuários de localização)
        $localizacoesQuery = Localizacao::where('ativo', true);
        if ($usuarioRestrito && !empty($localizacoesPermitidas)) {
            $localizacoesQuery->whereIn('id', $localizacoesPermitidas);
        }
        $localizacoes = $localizacoesQuery->orderBy('nome_localizacao')->get();

        // IDs para organização do filtro (principal no topo, visualizações abaixo)
        $localizacaoPrincipalId = $usuarioRestrito ? $user->localizacao_id : null;
        $localizacoesVisualizacaoIds = $usuarioRestrito ? $user->visualizacoes()->pluck('localizacoes.id')->toArray() : [];

        // Etapas de Produção para filtro
        $etapasProducao = \App\Models\EtapaProducao::where('ativo', true)
            ->orderBy('ordem')
            ->get();

        // Marcas para filtro
        $marcas = \App\Models\Marca::where('ativo', true)
            ->orderBy('nome_marca')
            ->get();

        return view('localizacao-capacidade.dashboard', compact('dadosDashboard', 'mes', 'ano', 'localizacoes', 'localizacaoId', 'etapasProducao', 'etapaId', 'marcas', 'marcaId', 'referencia', 'usuarioRestrito', 'localizacaoPrincipalId', 'localizacoesVisualizacaoIds'));
    }

    /**
     * Gerar PDF do relatório de planejamento
     */
    public function gerarRelatorioPDF(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);
        $localizacaoId = $request->input('localizacao_id');
        $etapaId = $request->input('etapa_id');
        $marcaId = $request->input('marca_id');
        $referencia = $request->input('referencia');
        $orientation = $request->input('orientation', 'landscape');

        // Buscar capacidades do período
        $query = LocalizacaoCapacidadeMensal::with('localizacao')
            ->where('mes', $mes)
            ->where('ano', $ano);

        if ($localizacaoId) {
            $query->where('localizacao_id', $localizacaoId);
        }

        $capacidades = $query->get();

        // Mapear dados para o formato esperado pela view (mesma lógica do dashboard)
        $dadosDashboard = $capacidades->map(function ($capacidade) use ($mes, $ano, $etapaId, $marcaId, $referencia) {
            // Buscar produtos diretamente pela data_prevista_faccao em produto_localizacao
            $produtosQuery = \App\Models\Produto::whereHas('localizacoes', function($query) use ($capacidade, $mes, $ano, $etapaId) {
                $query->where('localizacao_id', $capacidade->localizacao_id)
                      ->whereMonth('data_prevista_faccao', $mes)
                      ->whereYear('data_prevista_faccao', $ano);

                // Filtrar por etapa se selecionada
                if ($etapaId) {
                    $query->where('etapa_atual_id', $etapaId);
                }
            })
            ->with(['marca', 'grupoProduto', 'status', 'observacoes', 'direcionamentoComercial', 'localizacoes' => function($query) use ($capacidade, $mes, $ano, $etapaId) {
                $query->where('localizacao_id', $capacidade->localizacao_id)
                      ->whereMonth('data_prevista_faccao', $mes)
                      ->whereYear('data_prevista_faccao', $ano);

                // Filtrar por etapa se selecionada
                if ($etapaId) {
                    $query->where('etapa_atual_id', $etapaId);
                }
            }]);

            // Filtrar por marca se selecionada
            if ($marcaId) {
                $produtosQuery->where('marca_id', $marcaId);
            }

            // Filtrar por referência se informada
            if ($referencia) {
                $produtosQuery->where('referencia', 'like', "%{$referencia}%");
            }

            $produtos = $produtosQuery->get()
            ->map(function($produto) {
                $produto->quantidade_alocada = $produto->localizacoes->sum('pivot.quantidade');
                return $produto;
            });

            $produtosPrevistos = $produtos->sum('quantidade_alocada');

            return [
                'localizacao' => $capacidade->localizacao,
                'capacidade' => $capacidade->capacidade,
                'observacoes' => $capacidade->observacoes,
                'produtos_previstos' => $produtosPrevistos,
                'produtos' => $produtos,
                'saldo' => $capacidade->capacidade - $produtosPrevistos,
                'percentual' => $capacidade->capacidade > 0 ? round(($produtosPrevistos / $capacidade->capacidade) * 100, 1) : 0,
                'acima_capacidade' => $produtosPrevistos > $capacidade->capacidade
            ];
        });

        // Nome do mês
        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];
        $mesNome = $meses[$mes] ?? '';

        // Etapas de Produção para cores e ícones no PDF
        $etapasProducao = \App\Models\EtapaProducao::where('ativo', true)
            ->orderBy('ordem')
            ->get();

        $view = $orientation === 'portrait' ? 'localizacao-capacidade.relatorio-retrato-pdf' : 'localizacao-capacidade.relatorio-pdf';

        $pdf = \PDF::loadView($view, compact('dadosDashboard', 'mes', 'ano', 'mesNome', 'etapasProducao'))
                   ->setPaper('a4', $orientation);

        return $pdf->stream("Relatorio_Planejamento_{$mesNome}_{$ano}.pdf");
    }

    /**
     * Gerar PDF da listagem de capacidades mensais
     */
    public function gerarPDFListagem(Request $request)
    {
        $query = LocalizacaoCapacidadeMensal::with('localizacao');

        // Aplicar filtros
        if ($request->filled('localizacao_id')) {
            $query->where('localizacao_id', $request->localizacao_id);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }

        $capacidades = $query->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->orderBy('localizacao_id')
            ->get();

        // Carregar localizações para exibir nomes nos filtros
        $localizacoes = Localizacao::where('ativo', true)
            ->orderBy('nome_localizacao')
            ->get();

        // Preparar dados dos filtros para exibição no PDF
        $filtros = [
            'localizacao' => $request->filled('localizacao_id')
                ? $localizacoes->firstWhere('id', $request->localizacao_id)->nome_localizacao ?? 'N/A'
                : 'Todas',
            'mes' => $request->filled('mes')
                ? ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'][$request->mes]
                : 'Todos',
            'ano' => $request->filled('ano') ? $request->ano : 'Todos'
        ];

        $pdf = \PDF::loadView('localizacao-capacidade.listagem-pdf', compact('capacidades', 'filtros'));

        return $pdf->stream("Listagem_Capacidades_Mensais_" . now()->format('d_m_Y_H_i') . ".pdf");
    }

    /**
     * Exibir calendário de produção
     */
    public function calendario(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);
        $localizacaoId = $request->input('localizacao_id');
        $referencia = $request->input('referencia');

        // Verificar se o usuário é um usuário de localização (facção/setor)
        $user = auth()->user();
        $usuarioRestrito = false;
        $localizacoesPermitidas = [];

        if ($user->isUsuarioLocalizacao()) {
            $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
            $usuarioRestrito = true;

            // Se não há filtro, usar a localização principal por padrão
            if (!$localizacaoId) {
                $localizacaoId = $user->localizacao_id;
            } elseif (!in_array($localizacaoId, $localizacoesPermitidas)) {
                // Se há filtro inválido, voltar para a principal
                $localizacaoId = $user->localizacao_id;
            }
        }

        // Buscar todas as alocações do mês com suas datas
        $query = \DB::table('produto_localizacao')
            ->join('produtos', 'produto_localizacao.produto_id', '=', 'produtos.id')
            ->join('localizacoes', 'produto_localizacao.localizacao_id', '=', 'localizacoes.id')
            ->select(
                'produtos.id as produto_id',
                'produtos.referencia',
                'localizacoes.nome_localizacao',
                'localizacoes.nome_reduzido',
                'produto_localizacao.data_prevista_faccao',
                'produto_localizacao.data_envio_faccao',
                'produto_localizacao.data_retorno_faccao',
                'produto_localizacao.data_entrega_faccao',
                'produto_localizacao.quantidade'
            )
            ->whereNull('produto_localizacao.deleted_at')
            ->whereNull('produtos.deleted_at')
            ->where(function($q) use ($mes, $ano) {
                $q->where(function($sub) use ($mes, $ano) {
                    $sub->whereMonth('produto_localizacao.data_prevista_faccao', $mes)
                        ->whereYear('produto_localizacao.data_prevista_faccao', $ano);
                })
                ->orWhere(function($sub) use ($mes, $ano) {
                    $sub->whereMonth('produto_localizacao.data_envio_faccao', $mes)
                        ->whereYear('produto_localizacao.data_envio_faccao', $ano);
                })
                ->orWhere(function($sub) use ($mes, $ano) {
                    $sub->whereMonth('produto_localizacao.data_retorno_faccao', $mes)
                        ->whereYear('produto_localizacao.data_retorno_faccao', $ano);
                })
                ->orWhere(function($sub) use ($mes, $ano) {
                    $sub->whereMonth('produto_localizacao.data_entrega_faccao', $mes)
                        ->whereYear('produto_localizacao.data_entrega_faccao', $ano);
                });
            });

        if ($localizacaoId) {
            $query->where('produto_localizacao.localizacao_id', $localizacaoId);
        } elseif ($usuarioRestrito && !empty($localizacoesPermitidas)) {
            // Se usuário restrito sem filtro específico, mostrar apenas suas localizações permitidas
            $query->whereIn('produto_localizacao.localizacao_id', $localizacoesPermitidas);
        }

        if ($referencia) {
            $query->where('produtos.referencia', 'like', "%{$referencia}%");
        }

        $alocacoes = $query->get();

        // Organizar eventos por dia
        $eventos = [];
        $tiposCores = [
            'previsao' => ['cor' => '#3B82F6', 'label' => 'Previsão'],
            'envio' => ['cor' => '#F59E0B', 'label' => 'Envio'],
            'retorno' => ['cor' => '#10B981', 'label' => 'Retorno'],
            'entrega' => ['cor' => '#8B5CF6', 'label' => 'Entrega'],
        ];

        foreach ($alocacoes as $alocacao) {
            $nomeLocal = $alocacao->nome_reduzido ?: mb_substr($alocacao->nome_localizacao, 0, 10);

            // Data Previsão
            if ($alocacao->data_prevista_faccao) {
                $data = \Carbon\Carbon::parse($alocacao->data_prevista_faccao);
                if ($data->month == $mes && $data->year == $ano) {
                    $dia = $data->day;
                    $eventos[$dia][] = [
                        'produto_id' => $alocacao->produto_id,
                        'referencia' => $alocacao->referencia,
                        'localizacao' => $nomeLocal,
                        'tipo' => 'previsao',
                        'cor' => $tiposCores['previsao']['cor'],
                        'quantidade' => $alocacao->quantidade,
                        'data' => $data->format('d/m/Y'),
                    ];
                }
            }

            // Data Envio
            if ($alocacao->data_envio_faccao) {
                $data = \Carbon\Carbon::parse($alocacao->data_envio_faccao);
                if ($data->month == $mes && $data->year == $ano) {
                    $dia = $data->day;
                    $eventos[$dia][] = [
                        'produto_id' => $alocacao->produto_id,
                        'referencia' => $alocacao->referencia,
                        'localizacao' => $nomeLocal,
                        'tipo' => 'envio',
                        'cor' => $tiposCores['envio']['cor'],
                        'quantidade' => $alocacao->quantidade,
                        'data' => $data->format('d/m/Y'),
                    ];
                }
            }

            // Data Retorno
            if ($alocacao->data_retorno_faccao) {
                $data = \Carbon\Carbon::parse($alocacao->data_retorno_faccao);
                if ($data->month == $mes && $data->year == $ano) {
                    $dia = $data->day;
                    $eventos[$dia][] = [
                        'produto_id' => $alocacao->produto_id,
                        'referencia' => $alocacao->referencia,
                        'localizacao' => $nomeLocal,
                        'tipo' => 'retorno',
                        'cor' => $tiposCores['retorno']['cor'],
                        'quantidade' => $alocacao->quantidade,
                        'data' => $data->format('d/m/Y'),
                    ];
                }
            }

            // Data Entrega
            if ($alocacao->data_entrega_faccao) {
                $data = \Carbon\Carbon::parse($alocacao->data_entrega_faccao);
                if ($data->month == $mes && $data->year == $ano) {
                    $dia = $data->day;
                    $eventos[$dia][] = [
                        'produto_id' => $alocacao->produto_id,
                        'referencia' => $alocacao->referencia,
                        'localizacao' => $nomeLocal,
                        'tipo' => 'entrega',
                        'cor' => $tiposCores['entrega']['cor'],
                        'quantidade' => $alocacao->quantidade,
                        'data' => $data->format('d/m/Y'),
                    ];
                }
            }
        }

        // Localizações para filtro (restrito para usuários de localização)
        $localizacoesQuery = Localizacao::where('ativo', true);
        if ($usuarioRestrito && !empty($localizacoesPermitidas)) {
            $localizacoesQuery->whereIn('id', $localizacoesPermitidas);
        }
        $localizacoes = $localizacoesQuery->orderBy('nome_localizacao')->get();

        // Dados do calendário
        $primeiroDia = \Carbon\Carbon::createFromDate($ano, $mes, 1);
        $ultimoDia = $primeiroDia->copy()->endOfMonth();
        $diasNoMes = $ultimoDia->day;
        $diaSemanaInicio = $primeiroDia->dayOfWeek; // 0 = Domingo

        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];
        $mesNome = $meses[$mes];

        return view('localizacao-capacidade.calendario', compact(
            'eventos', 'tiposCores', 'mes', 'ano', 'mesNome',
            'localizacoes', 'localizacaoId', 'diasNoMes', 'diaSemanaInicio'
        ));
    }
}
