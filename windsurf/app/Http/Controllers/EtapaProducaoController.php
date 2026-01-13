<?php

namespace App\Http\Controllers;

use App\Models\EtapaProducao;
use App\Models\EtapaTransicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtapaProducaoController extends Controller
{
    /**
     * Listagem de etapas de produção
     */
    public function index(Request $request)
    {
        $query = EtapaProducao::query();

        // Filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo === '1');
        }

        $etapas = $query->orderBy('ordem')->paginate(15)->appends($request->query());

        // Carregar todas as etapas ativas com transições para o diagrama de fluxo
        $etapasFluxo = EtapaProducao::where('ativo', true)
            ->with(['transicoesOrigem.etapaDestino'])
            ->orderBy('ordem')
            ->get();

        return view('etapas-producao.index', compact('etapas', 'etapasFluxo'));
    }

    /**
     * Visualizar fluxo de etapas em página cheia
     */
    public function visualizarFluxo()
    {
        $etapasFluxo = EtapaProducao::where('ativo', true)
            ->with(['transicoesOrigem.etapaDestino'])
            ->orderBy('ordem')
            ->get();

        return view('etapas-producao.fluxo', compact('etapasFluxo'));
    }

    /**
     * Visualizar fluxo de etapas com as quantidades de produtos em cada etapa
     */
    public function visualizarFluxoQuantidades()
    {
        // Carregar todas as etapas ativas com transições
        $etapasFluxo = EtapaProducao::where('ativo', true)
            ->with(['transicoesOrigem.etapaDestino'])
            ->orderBy('ordem')
            ->get();

        // Contar as quantidades de produtos por etapa atual
        // Ignora os que não tiverem etapas (etapa_atual_id null)
        $quantidadesPorEtapa = DB::table('produto_localizacao')
            ->select('etapa_atual_id', DB::raw('SUM(quantidade) as total_quantidade'))
            ->whereNotNull('etapa_atual_id')
            ->whereNull('deleted_at') // Tabela pivot pode ter soft deletes se configurada
            ->groupBy('etapa_atual_id')
            ->pluck('total_quantidade', 'etapa_atual_id')
            ->toArray();

        // Adicionar a quantidade a cada etapa para facilitar na view
        foreach ($etapasFluxo as $etapa) {
            $etapa->quantidade_produtos = $quantidadesPorEtapa[$etapa->id] ?? 0;
        }

        return view('etapas-producao.fluxo-quantidades', compact('etapasFluxo'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $cores = EtapaProducao::coresDisponiveis();
        $etapas = EtapaProducao::where('ativo', true)->orderBy('ordem')->get();
        $localizacoes = \App\Models\Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get();

        return view('etapas-producao.create', compact('cores', 'etapas', 'localizacoes'));
    }

    /**
     * Salvar nova etapa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:255',
            'cor' => 'required|string|max:20',
            'icone' => 'nullable|string|max:50',
            'localizacao_id' => 'nullable|integer|exists:localizacoes,id',
            'ativo' => 'sometimes|boolean',
            'ordem' => 'required|integer|min:0',
            'transicoes' => 'nullable|array',
            'transicoes.*.etapa_destino_id' => 'nullable|integer|exists:etapas_producao,id',
            'transicoes.*.label_botao' => 'nullable|string|max:50',
            'transicoes.*.cor_botao' => 'nullable|string|max:20'
        ]);

        $etapa = EtapaProducao::create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?: null,
            'cor' => $validated['cor'],
            'icone' => $validated['icone'] ?: null,
            'localizacao_id' => $validated['localizacao_id'] ?: null,
            'ativo' => $request->has('ativo'),
            'ordem' => $validated['ordem']
        ]);

        // Criar transições se fornecidas
        if (!empty($validated['transicoes'])) {
            $ordem = 0;
            foreach ($validated['transicoes'] as $transicao) {
                if (!empty($transicao['etapa_destino_id'])) {
                    EtapaTransicao::create([
                        'etapa_origem_id' => $etapa->id,
                        'etapa_destino_id' => $transicao['etapa_destino_id'],
                        'label_botao' => $transicao['label_botao'] ?? null,
                        'cor_botao' => $transicao['cor_botao'] ?? 'blue',
                        'ativo' => true,
                        'ordem' => $ordem++
                    ]);
                }
            }
        }

        return redirect()->route('etapas-producao.index')
            ->with('success', 'Etapa de produção criada com sucesso!');
    }

    /**
     * Exibir detalhes
     */
    public function show(EtapaProducao $etapasProducao)
    {
        $etapasProducao->load(['transicoesOrigem.etapaDestino', 'transicoesDestino.etapaOrigem']);

        return view('etapas-producao.show', ['etapa' => $etapasProducao]);
    }

    /**
     * Formulário de edição
     */
    public function edit(EtapaProducao $etapasProducao)
    {
        $cores = EtapaProducao::coresDisponiveis();
        $etapas = EtapaProducao::where('ativo', true)
            ->where('id', '!=', $etapasProducao->id)
            ->orderBy('ordem')
            ->get();
        $etapasProducao->load('transicoesOrigem.etapaDestino');
        $localizacoes = \App\Models\Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get();

        return view('etapas-producao.edit', [
            'etapa' => $etapasProducao,
            'cores' => $cores,
            'etapas' => $etapas,
            'localizacoes' => $localizacoes
        ]);
    }

    /**
     * Atualizar etapa
     */
    public function update(Request $request, EtapaProducao $etapasProducao)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:255',
            'cor' => 'required|string|max:20',
            'icone' => 'nullable|string|max:50',
            'localizacao_id' => 'nullable|integer|exists:localizacoes,id',
            'ativo' => 'sometimes|boolean',
            'ordem' => 'required|integer|min:0',
            'transicoes' => 'nullable|array',
            'transicoes.*.etapa_destino_id' => 'nullable|integer|exists:etapas_producao,id',
            'transicoes.*.label_botao' => 'nullable|string|max:50',
            'transicoes.*.cor_botao' => 'nullable|string|max:20'
        ]);

        $etapasProducao->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?: null,
            'cor' => $validated['cor'],
            'icone' => $validated['icone'] ?: null,
            'localizacao_id' => $validated['localizacao_id'] ?: null,
            'ativo' => $request->has('ativo'),
            'ordem' => $validated['ordem']
        ]);

        // Atualizar transições
        $etapasProducao->transicoesOrigem()->delete();

        if (!empty($validated['transicoes'])) {
            $ordem = 0;
            foreach ($validated['transicoes'] as $transicao) {
                if (!empty($transicao['etapa_destino_id'])) {
                    EtapaTransicao::create([
                        'etapa_origem_id' => $etapasProducao->id,
                        'etapa_destino_id' => $transicao['etapa_destino_id'],
                        'label_botao' => $transicao['label_botao'] ?? null,
                        'cor_botao' => $transicao['cor_botao'] ?? 'blue',
                        'ativo' => true,
                        'ordem' => $ordem++
                    ]);
                }
            }
        }

        return redirect()->route('etapas-producao.index')
            ->with('success', 'Etapa de produção atualizada com sucesso!');
    }

    /**
     * Excluir etapa
     */
    public function destroy(EtapaProducao $etapasProducao)
    {
        $etapasProducao->delete();

        return redirect()->route('etapas-producao.index')
            ->with('success', 'Etapa de produção excluída com sucesso!');
    }
}
