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

        if ($request->filled('contexto')) {
            $query->where('contexto', $request->contexto);
        }

        $etapas = $query->orderBy('contexto')->orderBy('ordem')->paginate(15)->appends($request->query());

        // Carregar etapas ativas com transições para o diagrama (filtro opcional por contexto)
        $fluxoQuery = EtapaProducao::where('ativo', true)
            ->with(['transicoesOrigem.etapaDestino']);

        if ($request->filled('contexto')) {
            $fluxoQuery->where('contexto', $request->contexto);
        }

        $etapasFluxo = $fluxoQuery->orderBy('ordem')->get();

        $contextos = EtapaProducao::contextosDisponiveis();

        return view('etapas-producao.index', compact('etapas', 'etapasFluxo', 'contextos'));
    }

    /**
     * Visualizar fluxo de etapas em página cheia
     */
    public function visualizarFluxo(Request $request)
    {
        $contexto = $request->get('contexto');

        $query = EtapaProducao::where('ativo', true)
            ->with(['transicoesOrigem.etapaDestino']);

        if ($contexto && array_key_exists($contexto, EtapaProducao::contextosDisponiveis())) {
            $query->where('contexto', $contexto);
        }

        $etapasFluxo = $query->orderBy('ordem')->get();
        $contextos = EtapaProducao::contextosDisponiveis();

        return view('etapas-producao.fluxo', compact('etapasFluxo', 'contexto', 'contextos'));
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
        $contextoForm = request('contexto', EtapaProducao::CONTEXTO_LOCALIZACAO);
        $etapas = $this->etapasParaTransicoes($contextoForm);
        $localizacoes = \App\Models\Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get();
        $contextos = EtapaProducao::contextosDisponiveis();

        return view('etapas-producao.create', compact('cores', 'etapas', 'localizacoes', 'contextos', 'contextoForm'));
    }

    /**
     * Salvar nova etapa
     */
    public function store(Request $request)
    {
        $validated = $this->validateEtapaRequest($request);

        $contexto = $validated['contexto'];
        $iniciaLogistica = $contexto === EtapaProducao::CONTEXTO_LOGISTICA && $request->has('inicia_logistica');

        $etapa = EtapaProducao::create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?: null,
            'cor' => $validated['cor'],
            'icone' => $validated['icone'] ?: null,
            'contexto' => $contexto,
            'inicia_logistica' => $iniciaLogistica,
            'localizacao_id' => $validated['localizacao_id'] ?: null,
            'ativo' => $request->has('ativo'),
            'ordem' => $validated['ordem'],
            'obriga_data_entrega_faccao' => $contexto === EtapaProducao::CONTEXTO_LOCALIZACAO && $request->has('obriga_data_entrega_faccao'),
        ]);

        if ($iniciaLogistica) {
            $this->syncEtapaIniciaLogistica($etapa);
        }

        $this->syncTransicoes($etapa, $validated['transicoes'] ?? []);

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
        $etapas = $this->etapasParaTransicoes($etapasProducao->contexto, $etapasProducao->id);
        $etapasProducao->load('transicoesOrigem.etapaDestino');
        $localizacoes = \App\Models\Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get();
        $contextos = EtapaProducao::contextosDisponiveis();

        return view('etapas-producao.edit', [
            'etapa' => $etapasProducao,
            'cores' => $cores,
            'etapas' => $etapas,
            'localizacoes' => $localizacoes,
            'contextos' => $contextos,
        ]);
    }

    /**
     * Atualizar etapa
     */
    public function update(Request $request, EtapaProducao $etapasProducao)
    {
        $validated = $this->validateEtapaRequest($request, $etapasProducao);

        $contexto = $etapasProducao->slug
            ? $etapasProducao->contexto
            : $validated['contexto'];

        $iniciaLogistica = $contexto === EtapaProducao::CONTEXTO_LOGISTICA && $request->has('inicia_logistica');

        $etapasProducao->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?: null,
            'cor' => $validated['cor'],
            'icone' => $validated['icone'] ?: null,
            'contexto' => $contexto,
            'inicia_logistica' => $iniciaLogistica,
            'localizacao_id' => $validated['localizacao_id'] ?: null,
            'ativo' => $request->has('ativo'),
            'ordem' => $validated['ordem'],
            'obriga_data_entrega_faccao' => $contexto === EtapaProducao::CONTEXTO_LOCALIZACAO && $request->has('obriga_data_entrega_faccao'),
        ]);

        if ($iniciaLogistica) {
            $this->syncEtapaIniciaLogistica($etapasProducao);
        } elseif ($etapasProducao->wasChanged('inicia_logistica') || !$iniciaLogistica) {
            // mantido false no update acima
        }

        $this->syncTransicoes($etapasProducao, $validated['transicoes'] ?? []);

        return redirect()->route('etapas-producao.index')
            ->with('success', 'Etapa de produção atualizada com sucesso!');
    }

    /**
     * Excluir etapa
     */
    public function destroy(EtapaProducao $etapasProducao)
    {
        if ($etapasProducao->isLogistica()) {
            return redirect()->route('etapas-producao.index')
                ->with('error', 'Etapas do fluxo logístico não podem ser excluídas (slug: ' . $etapasProducao->slug . ').');
        }

        $etapasProducao->delete();

        return redirect()->route('etapas-producao.index')
            ->with('success', 'Etapa de produção excluída com sucesso!');
    }

    private function validateEtapaRequest(Request $request, ?EtapaProducao $etapa = null): array
    {
        $contextos = implode(',', array_keys(EtapaProducao::contextosDisponiveis()));

        $rules = [
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:255',
            'cor' => 'required|string|max:20',
            'icone' => 'nullable|string|max:50',
            'contexto' => 'required|in:' . $contextos,
            'localizacao_id' => 'nullable|integer|exists:localizacoes,id',
            'ativo' => 'sometimes|boolean',
            'ordem' => 'required|integer|min:0',
            'transicoes' => 'nullable|array',
            'transicoes.*.etapa_destino_id' => 'nullable|integer|exists:etapas_producao,id',
            'transicoes.*.label_botao' => 'nullable|string|max:50',
            'transicoes.*.cor_botao' => 'nullable|string|max:20',
        ];

        if ($etapa?->slug) {
            unset($rules['contexto']);
        }

        return $request->validate($rules);
    }

    /**
     * Etapas candidatas a destino de transição (mesmo contexto + etapa de início logístico).
     */
    private function etapasParaTransicoes(string $contexto, ?int $excluirId = null)
    {
        $query = EtapaProducao::where('ativo', true)
            ->where(function ($q) use ($contexto) {
                $q->where('contexto', $contexto);
                if ($contexto === EtapaProducao::CONTEXTO_LOCALIZACAO) {
                    $q->orWhere('inicia_logistica', true);
                }
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->orderBy('contexto')->orderBy('ordem')->get();
    }

    private function syncEtapaIniciaLogistica(EtapaProducao $etapa): void
    {
        EtapaProducao::where('id', '!=', $etapa->id)
            ->where('inicia_logistica', true)
            ->update(['inicia_logistica' => false]);
    }

    private function syncTransicoes(EtapaProducao $origem, array $transicoes): void
    {
        $origem->transicoesOrigem()->delete();

        $ordem = 0;
        foreach ($transicoes as $transicao) {
            if (empty($transicao['etapa_destino_id'])) {
                continue;
            }

            $destino = EtapaProducao::find($transicao['etapa_destino_id']);
            if (!$destino || !$origem->podeTransicionarPara($destino)) {
                continue;
            }

            EtapaTransicao::create([
                'etapa_origem_id' => $origem->id,
                'etapa_destino_id' => $destino->id,
                'label_botao' => $transicao['label_botao'] ?? null,
                'cor_botao' => $transicao['cor_botao'] ?? 'blue',
                'ativo' => true,
                'ordem' => $ordem++,
            ]);
        }
    }
}
