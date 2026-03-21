<?php

namespace App\Http\Controllers;

use App\Models\ColetaLogistica;
use App\Models\EtapaProducao;
use App\Models\Localizacao;
use App\Models\ProdutoLocalizacao;
use App\Models\Veiculo;
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

        // Etapa "Aguardando Retirada" por slug
        $etapaAguardandoRetirada = EtapaProducao::porSlug(EtapaProducao::SLUG_AGUARDANDO_RETIRADA);

        // Filtros
        $localizacaoId = $request->get('localizacao_id');
        $referencia = trim((string) $request->get('referencia', ''));
        $historicoDataDe = $request->get('historico_de');
        $historicoDataAte = $request->get('historico_ate');

        $tabelaColetasExiste = Schema::hasTable('coletas_logisticas');
        $tabelaVeiculosExiste = Schema::hasTable('veiculos');

        // Produtos aguardando retirada
        $aguardandoRetirada = collect();
        if ($etapaAguardandoRetirada) {
            $query = ProdutoLocalizacao::with(['produto', 'localizacao', 'etapaAtual'])
                ->where('etapa_atual_id', $etapaAguardandoRetirada->id);

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
        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
        $destinosDisponiveis = Localizacao::where('ativo', true)
            ->whereIn('id', $localizacoesPermitidas)
            ->orderBy('nome_localizacao')
            ->get();

        return view('logistica-coleta.index', compact(
            'aguardandoRetirada',
            'coletasAtivas',
            'historicoColetas',
            'localizacoes',
            'veiculos',
            'destinosDisponiveis',
            'localizacaoId',
            'referencia',
            'historicoDataDe',
            'historicoDataAte',
        ));
    }

    /**
     * Motorista agenda coleta
     */
    public function agendar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'produto_localizacao_id' => 'required|integer|exists:produto_localizacao,id',
            'veiculo_id' => 'required|integer|exists:veiculos,id',
            'destino_localizacao_id' => 'required|integer|exists:localizacoes,id',
            'inicio_previsto_em' => 'required|date|after_or_equal:today',
            'retorno_previsto_em' => 'required|date|after:inicio_previsto_em',
            'observacao_motorista' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        // Validar que o destino está nas localizações permitidas do motorista
        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
        if (!in_array((int) $validated['destino_localizacao_id'], $localizacoesPermitidas)) {
            return back()->with('error', 'Você não tem permissão para enviar produtos a este destino.');
        }

        $produtoLocalizacao = ProdutoLocalizacao::findOrFail($validated['produto_localizacao_id']);

        // Verificar se produto está em AGUARDANDO RETIRADA
        $etapaAguardandoRetirada = EtapaProducao::porSlug(EtapaProducao::SLUG_AGUARDANDO_RETIRADA);
        if (!$etapaAguardandoRetirada || $produtoLocalizacao->etapa_atual_id !== $etapaAguardandoRetirada->id) {
            return back()->with('error', 'Este produto não está na etapa Aguardando Retirada.');
        }

        $inicio = $validated['inicio_previsto_em'];
        $retorno = $validated['retorno_previsto_em'];

        // Verificar coleta ativa existente
        if (ColetaLogistica::temColetaAtiva($produtoLocalizacao->id)) {
            return back()->with('error', 'Já existe uma coleta ativa para este produto.');
        }

        // Verificar conflito de motorista
        if (ColetaLogistica::temConflitoMotorista($user->id, $inicio, $retorno)) {
            return back()->with('error', 'Você já possui uma coleta agendada neste horário.');
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
                'motorista_user_id' => $user->id,
                'veiculo_id' => $validated['veiculo_id'],
                'destino_localizacao_id' => $validated['destino_localizacao_id'],
                'inicio_previsto_em' => $inicio,
                'retorno_previsto_em' => $retorno,
                'status' => ColetaLogistica::STATUS_AGENDADO,
                'observacao_motorista' => $validated['observacao_motorista'] ?? null,
            ]);

            // Avançar etapa para AGUARDANDO MOTORISTA
            $etapaAguardandoMotorista = EtapaProducao::porSlug(EtapaProducao::SLUG_AGUARDANDO_MOTORISTA);
            if ($etapaAguardandoMotorista) {
                $produtoLocalizacao->avancarEtapa(
                    $etapaAguardandoMotorista->id,
                    $user->id,
                    'Coleta agendada pelo motorista ' . $user->name
                );
            }

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Coleta agendada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao agendar coleta: ' . $e->getMessage());
        }
    }

    /**
     * Responsável da origem confirma chegada do motorista → EM TRANSITO
     */
    public function confirmarChegadaOrigem(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        // Verificar se a coleta está em status agendado
        if ($coleta->status !== ColetaLogistica::STATUS_AGENDADO) {
            return back()->with('error', 'Esta coleta não está em status agendado.');
        }

        // Verificar se o usuário pertence à localização de origem
        $produtoLocalizacao = $coleta->produtoLocalizacao;
        if (!$user->isAdmin() && $user->localizacao_id !== $produtoLocalizacao->localizacao_id) {
            return back()->with('error', 'Você não tem permissão para confirmar chegada nesta localização.');
        }

        $validated = $request->validate([
            'observacao_origem' => 'nullable|string|max:1000',
            'back_url' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_EM_TRANSITO,
                'chegada_origem_em' => now(),
                'observacao_origem' => $validated['observacao_origem'] ?? null,
            ]);

            // Avançar etapa para EM TRANSITO
            $etapaEmTransito = EtapaProducao::porSlug(EtapaProducao::SLUG_EM_TRANSITO);
            if ($etapaEmTransito) {
                $produtoLocalizacao->avancarEtapa(
                    $etapaEmTransito->id,
                    $user->id,
                    'Motorista chegou na origem - confirmado por ' . $user->name
                );
            }

            DB::commit();

            $backUrl = $request->input('back_url');
            if ($backUrl) {
                return redirect($backUrl)->with('success', 'Chegada confirmada! Produto em trânsito.');
            }
            return redirect()->route('logistica-coleta.index')->with('success', 'Chegada confirmada! Produto em trânsito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao confirmar chegada: ' . $e->getMessage());
        }
    }

    /**
     * Funcionário do destino confirma recebimento → COLETADO
     */
    public function confirmarRecebimentoDestino(Request $request, ColetaLogistica $coleta): RedirectResponse
    {
        $user = auth()->user();

        // Verificar se a coleta está em trânsito
        if ($coleta->status !== ColetaLogistica::STATUS_EM_TRANSITO) {
            return back()->with('error', 'Esta coleta não está em trânsito.');
        }

        // Verificar se o usuário pertence à localização de destino
        if (!$user->isAdmin() && $user->localizacao_id !== $coleta->destino_localizacao_id) {
            return back()->with('error', 'Você não tem permissão para confirmar recebimento nesta localização.');
        }

        $validated = $request->validate([
            'observacao_destino' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_FINALIZADO,
                'recebido_destino_em' => now(),
                'observacao_destino' => $validated['observacao_destino'] ?? null,
            ]);

            // Avançar etapa para COLETADO
            $produtoLocalizacao = $coleta->produtoLocalizacao;
            $etapaColetado = EtapaProducao::porSlug(EtapaProducao::SLUG_COLETADO);
            if ($etapaColetado) {
                $produtoLocalizacao->avancarEtapa(
                    $etapaColetado->id,
                    $user->id,
                    'Produto recebido no destino - confirmado por ' . $user->name
                );
            }

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Produto coletado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao confirmar recebimento: ' . $e->getMessage());
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

            // Reverter etapa para AGUARDANDO RETIRADA
            $produtoLocalizacao = $coleta->produtoLocalizacao;
            $etapaAguardandoRetirada = EtapaProducao::porSlug(EtapaProducao::SLUG_AGUARDANDO_RETIRADA);
            if ($etapaAguardandoRetirada) {
                $produtoLocalizacao->avancarEtapa(
                    $etapaAguardandoRetirada->id,
                    $user->id,
                    'Coleta cancelada por ' . $user->name
                );
            }

            DB::commit();
            return redirect()->route('logistica-coleta.index')->with('success', 'Coleta cancelada. Produto retornou para Aguardando Retirada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cancelar coleta: ' . $e->getMessage());
        }
    }
}
