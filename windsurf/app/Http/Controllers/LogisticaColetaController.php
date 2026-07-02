<?php

namespace App\Http\Controllers;

use App\Models\ColetaLogistica;
use App\Models\EtapaProducao;
use App\Models\Localizacao;
use App\Models\ProdutoLocalizacao;
use App\Models\User;
use App\Models\Veiculo;
use App\Http\Requests\AgendarColetaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LogisticaColetaController extends Controller
{
    /**
     * Dashboard: lista produtos em AGUARDANDO RETIRADA + coletas ativas do motorista
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $etapaAgendamento = EtapaProducao::etapaInicioLogistica()
            ?? EtapaProducao::etapaLogisticaPorSlug(EtapaProducao::SLUG_AGENDAMENTO)
            ?? EtapaProducao::porSlug(EtapaProducao::SLUG_AGUARDANDO_RETIRADA);

        // Filtros
        $localizacaoId = $request->get('localizacao_id');
        $referencia = trim((string) $request->get('referencia', ''));
        $historicoDataDe = $request->get('historico_de');
        $historicoDataAte = $request->get('historico_ate');

        $tabelaColetasExiste = Schema::hasTable('coletas_logisticas');
        $tabelaVeiculosExiste = Schema::hasTable('veiculos');

        // Produtos disponíveis para entrada no fluxo logístico
        $aguardandoRetirada = collect();
        if ($etapaAgendamento) {
            $query = ProdutoLocalizacao::with(['produto', 'localizacao', 'etapaAtual'])
                ->where('etapa_atual_id', $etapaAgendamento->id);

            if ($tabelaColetasExiste) {
                $query->with(['coletaLogisticaAtiva.motorista', 'coletaLogisticaAtiva.veiculo', 'coletaLogisticaAtiva.destinoLocalizacao']);
            }

            if ($localizacaoId) {
                $query->where('localizacao_id', $localizacaoId);
            }

            if ($referencia !== '') {
                $query->whereHas('produto', function ($q) use ($referencia) {
                    $q->where('referencia', 'like', "%{$referencia}%");
                });
            }

            $aguardandoRetirada = $query->orderBy('created_at', 'asc')->paginate(20, ['*'], 'retirada_page');
        }

        // Coletas ativas (para todos ou só do motorista)
        $coletasAtivas = collect();
        if ($tabelaColetasExiste) {
            $coletasAtivasQuery = ColetaLogistica::with([
                'produtoLocalizacao.produto',
                'produtoLocalizacao.localizacao',
                'produtoLocalizacao.etapaAtual',
                'motorista',
                'veiculo',
                'destinoLocalizacao',
            ])->ativas()->orderBy('inicio_previsto_em');

            if (!$user->isAdmin()) {
                $coletasAtivasQuery->where('motorista_user_id', $user->id);
            }

            $coletasAtivas = $coletasAtivasQuery->get();
        }

        // Histórico de coletas finalizadas (coletado + cancelado)
        $historicoColetas = collect();
        if ($tabelaColetasExiste) {
            $historicoQuery = ColetaLogistica::with([
                'produtoLocalizacao.produto',
                'produtoLocalizacao.localizacao',
                'motorista',
                'veiculo',
                'destinoLocalizacao',
            ])->whereIn('status', [ColetaLogistica::STATUS_FINALIZADO, ColetaLogistica::STATUS_CANCELADO])
              ->orderBy('updated_at', 'desc');

            if (!$user->isAdmin()) {
                $historicoQuery->where('motorista_user_id', $user->id);
            }

            if ($historicoDataDe) {
                $historicoQuery->whereDate('updated_at', '>=', $historicoDataDe);
            }
            if ($historicoDataAte) {
                $historicoQuery->whereDate('updated_at', '<=', $historicoDataAte);
            }

            $historicoColetas = $historicoQuery->paginate(15, ['*'], 'historico_page');
        }

        // Dados para filtros
        $localizacoes = Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get();
        $veiculos = $tabelaVeiculosExiste ? Veiculo::ativos()->orderBy('placa')->get() : collect();
        $usuariosColeta = User::with('localizacao')->orderBy('name')->get();
        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
        $destinosDisponiveis = Localizacao::where('ativo', true)
            ->whereIn('id', $localizacoesPermitidas)
            ->orderBy('nome_localizacao')
            ->get();
        $coletaStatusLabels = ColetaLogistica::labelsStatus();

        return view('logistica-coleta.index', compact(
            'aguardandoRetirada',
            'coletasAtivas',
            'historicoColetas',
            'localizacoes',
            'veiculos',
            'usuariosColeta',
            'destinosDisponiveis',
            'localizacaoId',
            'referencia',
            'historicoDataDe',
            'historicoDataAte',
            'coletaStatusLabels',
        ));
    }

    /**
     * Motorista agenda coleta
     */
    public function agendar(AgendarColetaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = auth()->user();
        $motoristaId = (int) $validated['motorista_user_id'];
        $motorista = User::findOrFail($motoristaId);

        // Validar que o destino está nas localizações permitidas do motorista
        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
        if (!in_array((int) $validated['destino_localizacao_id'], $localizacoesPermitidas)) {
            return back()->with('error', 'Você não tem permissão para enviar produtos a este destino.');
        }

        $produtoLocalizacao = ProdutoLocalizacao::findOrFail($validated['produto_localizacao_id']);

        $etapaAgendamento = $this->etapaLogisticaObrigatoria(EtapaProducao::SLUG_AGENDAMENTO)
            ?? EtapaProducao::etapaInicioLogistica();
        if (!$etapaAgendamento || $produtoLocalizacao->etapa_atual_id !== $etapaAgendamento->id) {
            return back()->with('error', 'Este produto não está disponível para agendamento logístico.');
        }

        $inicio = $validated['inicio_previsto_em'];
        $retorno = $validated['retorno_previsto_em'];

        // Verificar coleta ativa existente
        if (ColetaLogistica::temColetaAtiva($produtoLocalizacao->id)) {
            return back()->with('error', 'Já existe uma coleta ativa para este produto.');
        }

        // Verificar conflito de motorista
        if (ColetaLogistica::temConflitoMotorista($motoristaId, $inicio, $retorno)) {
            return back()->with('error', 'O usuário selecionado já possui uma coleta agendada neste horário.');
        }

        // Verificar conflito de veículo
        if (ColetaLogistica::temConflitoVeiculo($validated['veiculo_id'], $inicio, $retorno)) {
            return back()->with('error', 'Este veículo já está agendado para este horário.');
        }

        DB::beginTransaction();
        try {
            // Criar coleta
            ColetaLogistica::create([
                'produto_localizacao_id' => $produtoLocalizacao->id,
                'motorista_user_id' => $motoristaId,
                'veiculo_id' => $validated['veiculo_id'],
                'destino_localizacao_id' => $validated['destino_localizacao_id'],
                'inicio_previsto_em' => $inicio,
                'retorno_previsto_em' => $retorno,
                'status' => ColetaLogistica::STATUS_AGENDADO,
                'observacao_motorista' => $validated['observacao_motorista'] ?? null,
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $produtoLocalizacao,
                EtapaProducao::SLUG_AGENDAMENTO,
                $user->id,
                'Coleta agendada para ' . $motorista->name . ' por ' . $user->name
            );

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Coleta agendada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao agendar coleta: ' . $e->getMessage());
        }
    }

    /**
     * Motorista solicita a retirada do produto na facção.
     */
    public function solicitarRetirada(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $coleta->motorista_user_id !== $user->id) {
            return back()->with('error', 'Você não tem permissão para solicitar a retirada deste produto.');
        }

        $produtoLocalizacao = $coleta->produtoLocalizacao;
        if ($coleta->status !== ColetaLogistica::STATUS_AGENDADO) {
            return back()->with('error', 'Esta coleta não está em um status válido para solicitar retirada.');
        }

        $validated = $request->validate([
            'observacao_motorista' => 'nullable|string|max:1000',
            'back_url' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'observacao_motorista' => $validated['observacao_motorista'] ?? $coleta->observacao_motorista,
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $produtoLocalizacao,
                EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA,
                $user->id,
                'Solicitação de retirada registrada pelo motorista ' . $user->name
            );

            DB::commit();

            $backUrl = $request->input('back_url');
            if ($backUrl) {
                return redirect($backUrl)->with('success', 'Retirada solicitada com sucesso.');
            }
            return redirect()->route('logistica-coleta.index')->with('success', 'Retirada solicitada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao solicitar retirada: ' . $e->getMessage());
        }
    }

    /**
     * Responsável da facção confirma a retirada e o produto entra automaticamente em trânsito.
     */
    public function confirmarRetiradaFaccao(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();
        $produtoLocalizacao = $coleta->produtoLocalizacao;

        if ($coleta->status !== ColetaLogistica::STATUS_AGENDADO) {
            return back()->with('error', 'Esta coleta não está em um status válido para confirmação de retirada.');
        }

        if (!$user->isAdmin() && $user->localizacao_id !== $produtoLocalizacao->localizacao_id) {
            return back()->with('error', 'Você não tem permissão para confirmar a retirada nesta localização.');
        }

        $validated = $request->validate([
            'observacao_origem' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_EM_TRANSITO,
                'chegada_origem_em' => now(),
                'observacao_origem' => $validated['observacao_origem'] ?? null,
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $produtoLocalizacao,
                EtapaProducao::SLUG_RETIRADA_CONFIRMADA_FACCAO,
                $user->id,
                'Retirada confirmada pela facção por ' . $user->name
            );

            $this->avancarProdutoParaEtapaLogistica(
                $produtoLocalizacao,
                EtapaProducao::SLUG_EM_TRANSITO,
                $user->id,
                'Produto entrou automaticamente em trânsito após confirmação da retirada pela facção'
            );

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Retirada confirmada! Produto em trânsito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao confirmar retirada: ' . $e->getMessage());
        }
    }

    /**
     * Motorista confirma a entrega na fábrica.
     */
    public function confirmarEntregaFabrica(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $coleta->motorista_user_id !== $user->id) {
            return back()->with('error', 'Você não tem permissão para confirmar a entrega desta coleta.');
        }

        if ($coleta->status !== ColetaLogistica::STATUS_EM_TRANSITO) {
            return back()->with('error', 'Esta coleta não está em trânsito.');
        }

        $validated = $request->validate([
            'observacao_destino' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_ENTREGUE,
                'observacao_destino' => $validated['observacao_destino'] ?? $coleta->observacao_destino,
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $coleta->produtoLocalizacao,
                EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA,
                $user->id,
                'Entrega na fábrica confirmada pelo motorista ' . $user->name
            );

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Entrega na fábrica confirmada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao confirmar entrega: ' . $e->getMessage());
        }
    }

    /**
     * Destino registra o check-in do produto entregue.
     */
    public function registrarCheckIn(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $user->localizacao_id !== $coleta->destino_localizacao_id) {
            return back()->with('error', 'Você não tem permissão para registrar check-in nesta localização.');
        }

        if (!in_array($coleta->status, [ColetaLogistica::STATUS_EM_TRANSITO, ColetaLogistica::STATUS_ENTREGUE], true)) {
            return back()->with('error', 'Esta coleta não está em um status válido para check-in.');
        }

        $validated = $request->validate([
            'observacao_destino' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            if ($coleta->status !== ColetaLogistica::STATUS_ENTREGUE) {
                $coleta->update([
                    'status' => ColetaLogistica::STATUS_ENTREGUE,
                ]);
            }

            if (!empty($validated['observacao_destino'])) {
                $coleta->update([
                    'observacao_destino' => $validated['observacao_destino'],
                ]);
            }

            $this->avancarProdutoParaEtapaLogistica(
                $coleta->produtoLocalizacao,
                EtapaProducao::SLUG_CHECK_IN,
                $user->id,
                'Check-in registrado por ' . $user->name
            );

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Check-in registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar check-in: ' . $e->getMessage());
        }
    }

    /**
     * Destino confirma a chegada final do produto na fábrica.
     */
    public function confirmarChegadaFabrica(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $user->localizacao_id !== $coleta->destino_localizacao_id) {
            return back()->with('error', 'Você não tem permissão para confirmar a chegada final nesta localização.');
        }

        if (!in_array($coleta->status, [ColetaLogistica::STATUS_EM_TRANSITO, ColetaLogistica::STATUS_ENTREGUE], true)) {
            return back()->with('error', 'Esta coleta não está em um status válido para encerramento.');
        }

        $validated = $request->validate([
            'observacao_destino' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_FINALIZADO,
                'recebido_destino_em' => now(),
                'observacao_destino' => $validated['observacao_destino'] ?? $coleta->observacao_destino,
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $coleta->produtoLocalizacao,
                EtapaProducao::SLUG_CHEGADA_PRODUTO_FABRICA,
                $user->id,
                'Chegada final do produto na fábrica confirmada por ' . $user->name
            );

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Chegada do produto confirmada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao confirmar chegada: ' . $e->getMessage());
        }
    }

    /**
     * Motorista cancela coleta (só em status agendado)
     */
    public function cancelar(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        // Verificar permissão: motorista da coleta ou admin
        if (!$user->isAdmin() && $coleta->motorista_user_id !== $user->id) {
            return back()->with('error', 'Você não tem permissão para cancelar esta coleta.');
        }

        if (!$coleta->podeCancelar()) {
            return back()->with('error', 'Esta coleta não pode ser cancelada (só é possível cancelar coletas em status agendado).');
        }

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_CANCELADO,
            ]);

            $produtoLocalizacao = $coleta->produtoLocalizacao;
            $etapaInicioLogistica = EtapaProducao::etapaInicioLogistica();
            if ($etapaInicioLogistica) {
                $produtoLocalizacao->avancarEtapa(
                    $etapaInicioLogistica->id,
                    $user->id,
                    'Coleta cancelada por ' . $user->name
                );
            }

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Coleta cancelada. Produto retornou ao início da logística.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cancelar coleta: ' . $e->getMessage());
        }
    }

    private function etapaLogisticaObrigatoria(string $slug): ?EtapaProducao
    {
        return EtapaProducao::etapaLogisticaPorSlug($slug) ?? EtapaProducao::porSlug($slug);
    }

    private function avancarProdutoParaEtapaLogistica(ProdutoLocalizacao $produtoLocalizacao, string $slug, int $userId, string $observacao): void
    {
        $etapa = $this->etapaLogisticaObrigatoria($slug);
        if (!$etapa) {
            throw new \RuntimeException('Etapa logística não encontrada para o slug: ' . $slug);
        }

        if ($produtoLocalizacao->etapa_atual_id === $etapa->id) {
            return;
        }

        $produtoLocalizacao->avancarEtapa($etapa->id, $userId, $observacao);
    }
}
