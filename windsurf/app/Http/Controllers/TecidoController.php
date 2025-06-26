<?php

namespace App\Http\Controllers;

use App\Models\Tecido;
use App\Services\EstoqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class TecidoController extends Controller
{
    protected $estoqueService;

    public function __construct(EstoqueService $estoqueService)
    {
        $this->estoqueService = $estoqueService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tecido::query();

        // Filtros
        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . $request->referencia . '%');
        }

        if ($request->filled('data_cadastro')) {
            $query->whereDate('data_cadastro', $request->data_cadastro);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'descricao');
        $orderDirection = $request->input('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $tecidos = $query->paginate(10);

        return view('tecidos.index', compact('tecidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tecidos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tecidos.create')
                ->withErrors($validator)
                ->withInput();
        }

        Tecido::create([
            'descricao' => $request->descricao,
            'referencia' => $request->referencia,
            'data_cadastro' => now(),
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('tecidos.index')
            ->with('success', 'Tecido criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tecido = Tecido::withTrashed()->findOrFail($id);
        return view('tecidos.show', compact('tecido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tecido = Tecido::withTrashed()->findOrFail($id);
        return view('tecidos.edit', compact('tecido'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tecidos.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $tecido = Tecido::withTrashed()->findOrFail($id);
        $tecido->update([
            'descricao' => $request->descricao,
            'referencia' => $request->referencia,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('tecidos.index')
            ->with('success', 'Tecido atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tecido = Tecido::withTrashed()->findOrFail($id);
        $tecido->delete(); // Soft delete

        return redirect()->route('tecidos.index')
            ->with('success', 'Tecido removido com sucesso.');
    }

    /**
     * Atualiza o estoque de um tecido específico
     */
    public function atualizarEstoque($id)
    {
        $tecido = Tecido::findOrFail($id);

        if (empty($tecido->referencia)) {
            return redirect()->route('tecidos.show', $id)
                ->with('error', 'Este tecido não possui referência para consulta de estoque.');
        }


        
        $resultado = $this->estoqueService->consultarEstoqueTecido($tecido->referencia);
        


        if ($resultado && !empty($resultado)) {
            // O resultado é um array indexado pela referência do tecido
            // Verificar se existe a referência do tecido no resultado
            if (isset($resultado[$tecido->referencia])) {
                $dadosEstoque = $resultado[$tecido->referencia];
            } else {
                // Se não encontrou a referência, usar valores padrão
                $dadosEstoque = [
                    'quantidade' => 0,
                    'data_consulta' => now(),
                    'detalhes' => []
                ];
            }

            // Atualizar o tecido com os dados de estoque
            $tecido->update([
                'quantidade_estoque' => $dadosEstoque['quantidade'],
                'ultima_consulta_estoque' => $dadosEstoque['data_consulta']
            ]);

            return redirect()->route('tecidos.show', $id)
                ->with('success', 'Estoque atualizado com sucesso. Quantidade total: ' . $dadosEstoque['quantidade']);
        }

        return redirect()->route('tecidos.show', $id)
            ->with('error', 'Não foi possível obter informações de estoque para este tecido.');
    }

    /**
     * Atualiza o estoque de todos os tecidos que possuem referência
     */
    public function atualizarTodosEstoques()
    {
        $tecidos = Tecido::whereNotNull('referencia')->get();

        if ($tecidos->isEmpty()) {
            return redirect()->route('tecidos.index')
                ->with('error', 'Não há tecidos com referência para atualizar.');
        }

        // Buscar todos os dados de estoque
        $response = Http::get(config('estoque.api_url'), [
            'empresa' => config('estoque.empresa'),
            'token' => config('estoque.token'),
            'armazenador' => config('estoque.armazenador')
        ]);

        if (!$response->successful()) {
            return redirect()->route('tecidos.index')
                ->with('error', 'Não foi possível obter informações de estoque.');
        }

        $todosEstoques = $response->json();
        $atualizados = 0;
        $dataConsulta = now();

        // Para cada tecido, filtrar os dados de estoque e atualizar
        foreach ($tecidos as $tecido) {
            if (empty($tecido->referencia)) {
                continue;
            }

            // Filtrar os dados de estoque para este tecido
            $estoqueDoTecido = array_filter($todosEstoques, function($item) use ($tecido) {
                return isset($item['Referencia']) && $item['Referencia'] === $tecido->referencia;
            });

            if (!empty($estoqueDoTecido)) {
                // Calcular a quantidade total
                $quantidadeTotal = 0;
                foreach ($estoqueDoTecido as $item) {
                    if (isset($item['Estoque'])) {
                        $quantidadeTotal += (float)$item['Estoque'];
                    }
                }

                // Atualizar o tecido
                $tecido->update([
                    'quantidade_estoque' => $quantidadeTotal,
                    'ultima_consulta_estoque' => $dataConsulta
                ]);

                $atualizados++;
            }
        }

        return redirect()->route('tecidos.index')
            ->with('success', "Estoque atualizado para {$atualizados} tecidos.");
    }

    /**
     * Método de debug para testar a consulta de estoque por referência
     */
    public function debugEstoque($referencia)
    {
        // Fazer a requisição diretamente para a API
        $response = Http::get(config('estoque.api_url'), [
            'empresa' => config('estoque.empresa'),
            'token' => config('estoque.token'),
            'armazenador' => config('estoque.armazenador')
            // Removido parâmetro referencia, vamos filtrar nos resultados
        ]);

        // Exibe a URL completa da requisição
        $url = config('estoque.api_url') . '?' . http_build_query([
            'empresa' => config('estoque.empresa'),
            'token' => config('estoque.token'),
            'armazenador' => config('estoque.armazenador')
            // Removido parâmetro referencia
        ]);

        // Exibe os dados brutos e processados
        $dadosBrutos = $response->json();
        
        // Verificar se a referência existe nos dados brutos
        $referenciaEncontrada = false;
        $itemReferencia = null;
        foreach ($dadosBrutos as $item) {
            if (isset($item['Referencia']) && $item['Referencia'] === $referencia) {
                $referenciaEncontrada = true;
                $itemReferencia = $item;
                break;
            }
        }
        
        $dadosProcessados = $this->estoqueService->consultarEstoqueTecido($referencia);

        // Retorna os dados para debug
        return response()->json([
            'url_requisicao' => $url,
            'status_code' => $response->status(),
            'dados_brutos_count' => count($dadosBrutos),
            'referencia_encontrada' => $referenciaEncontrada,
            'item_referencia' => $itemReferencia,
            'dados_processados' => $dadosProcessados,
            'referencia_buscada' => $referencia,
            'config_armazenador' => config('estoque.armazenador')
        ]);        
    }
}
