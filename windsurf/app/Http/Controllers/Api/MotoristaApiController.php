<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ColetaLogistica;
use App\Models\EtapaProducao;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class MotoristaApiController extends Controller
{
    /**
     * Login do motorista → retorna token Sanctum
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        // Tenta por e-mail primeiro, depois por nome (mesmo comportamento do sistema web)
        $user = \App\Models\User::where('email', $login)->first();
        if (!$user) {
            $user = \App\Models\User::where('name', $login)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Credenciais inválidas.'],
            ]);
        }

        // Revogar tokens anteriores do dispositivo
        $user->tokens()->where('name', 'motorista-pwa')->delete();

        $token = $user->createToken('motorista-pwa');

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'localizacao' => $user->localizacao?->nome_localizacao,
            ],
        ]);
    }

    /**
     * Logout → revogar token atual
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado.']);
    }

    /**
     * Perfil do motorista logado
     */
    public function perfil(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('localizacao');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'localizacao' => $user->localizacao?->nome_localizacao,
        ]);
    }

    /**
     * Lista coletas do motorista (ativas + finalizadas recentes)
     */
    public function coletas(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!Schema::hasTable('coletas_logisticas')) {
            return response()->json(['coletas' => []]);
        }

        $status = $request->get('status', 'ativas'); // ativas | historico

        $query = ColetaLogistica::with([
            'produtoLocalizacao.produto',
            'produtoLocalizacao.localizacao',
            'veiculo',
            'destinoLocalizacao',
        ])->where('motorista_user_id', $user->id);

        if ($status === 'historico') {
            $query->whereIn('status', [
                ColetaLogistica::STATUS_FINALIZADO,
                ColetaLogistica::STATUS_CANCELADO,
            ])->orderBy('updated_at', 'desc')->limit(20);
        } else {
            $query->ativas()->orderBy('inicio_previsto_em', 'asc');
        }

        $coletas = $query->get()->map(function ($coleta) {
            return $this->formatarColeta($coleta);
        });

        return response()->json(['coletas' => $coletas]);
    }

    /**
     * Detalhe de uma coleta
     */
    public function coletaDetalhe(Request $request, ColetaLogistica $coleta): JsonResponse
    {
        $user = $request->user();

        if ($coleta->motorista_user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $coleta->load([
            'produtoLocalizacao.produto',
            'produtoLocalizacao.localizacao',
            'produtoLocalizacao.etapaAtual',
            'veiculo',
            'destinoLocalizacao',
        ]);

        return response()->json(['coleta' => $this->formatarColeta($coleta, true)]);
    }

    /**
     * Motorista solicita a retirada do produto na facção.
     */
    public function solicitarRetirada(Request $request, ColetaLogistica $coleta): JsonResponse
    {
        $user = $request->user();

        if ($coleta->motorista_user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        if ($coleta->status !== ColetaLogistica::STATUS_AGENDADO) {
            return response()->json(['message' => 'Esta coleta não está em um status válido para solicitar retirada.'], 422);
        }

        $request->validate([
            'observacao' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'observacao_motorista' => $request->input('observacao'),
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $coleta->produtoLocalizacao,
                EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA,
                $user->id,
                'Solicitação de retirada registrada pelo motorista via app'
            );

            DB::commit();

            $coleta->refresh();
            $coleta->load([
                'produtoLocalizacao.produto',
                'produtoLocalizacao.localizacao',
                'veiculo',
                'destinoLocalizacao',
            ]);

            return response()->json([
                'message' => 'Retirada solicitada com sucesso!',
                'coleta' => $this->formatarColeta($coleta),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao solicitar retirada: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Motorista confirma entrega na fábrica.
     */
    public function confirmarEntregaFabrica(Request $request, ColetaLogistica $coleta): JsonResponse
    {
        $user = $request->user();

        if ($coleta->motorista_user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        if ($coleta->status !== ColetaLogistica::STATUS_EM_TRANSITO) {
            return response()->json(['message' => 'Esta coleta não está em trânsito.'], 422);
        }

        $request->validate([
            'observacao' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_ENTREGUE,
                'observacao_destino' => $request->input('observacao'),
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $coleta->produtoLocalizacao,
                EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA,
                $user->id,
                'Motorista confirmou entrega na fábrica via app'
            );

            DB::commit();

            $coleta->refresh();
            $coleta->load([
                'produtoLocalizacao.produto',
                'produtoLocalizacao.localizacao',
                'veiculo',
                'destinoLocalizacao',
            ]);

            return response()->json([
                'message' => 'Entrega na fábrica confirmada com sucesso!',
                'coleta' => $this->formatarColeta($coleta),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao confirmar entrega: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lista produtos disponíveis para coleta (AGUARDANDO RETIRADA sem coleta ativa)
     */
    public function disponiveis(Request $request): JsonResponse
    {
        if (!Schema::hasTable('coletas_logisticas')) {
            return response()->json(['produtos' => []]);
        }

        $etapaAgendamento = EtapaProducao::etapaInicioLogistica()
            ?? EtapaProducao::etapaLogisticaPorSlug(EtapaProducao::SLUG_AGENDAMENTO)
            ?? EtapaProducao::porSlug(EtapaProducao::SLUG_AGUARDANDO_RETIRADA);
        if (!$etapaAgendamento) {
            return response()->json(['produtos' => []]);
        }

        $produtos = \App\Models\ProdutoLocalizacao::with(['produto', 'localizacao'])
            ->where('etapa_atual_id', $etapaAgendamento->id)
            ->whereDoesntHave('coletasLogisticas', function ($q) {
                $q->ativas();
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($pl) {
                return [
                    'id' => $pl->id,
                    'referencia' => $pl->produto?->referencia ?? '-',
                    'descricao' => $pl->produto?->descricao ?? '-',
                    'quantidade' => $pl->quantidade ?? 0,
                    'origem' => $pl->localizacao?->nome_reduzido ?? $pl->localizacao?->nome_localizacao ?? '-',
                    'origem_id' => $pl->localizacao_id,
                    'aguardando_desde' => $pl->created_at?->diffForHumans(),
                ];
            });

        return response()->json(['produtos' => $produtos]);
    }

    /**
     * Lista veículos ativos
     */
    public function veiculos(Request $request): JsonResponse
    {
        if (!Schema::hasTable('veiculos')) {
            return response()->json(['veiculos' => []]);
        }

        $veiculos = \App\Models\Veiculo::where('ativo', true)
            ->orderBy('placa')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'placa' => $v->placa,
                    'descricao' => $v->descricao,
                ];
            });

        return response()->json(['veiculos' => $veiculos]);
    }

    /**
     * Lista destinos (localizações permitidas do motorista)
     */
    public function destinos(Request $request): JsonResponse
    {
        $user = $request->user();
        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();

        $destinos = \App\Models\Localizacao::where('ativo', true)
            ->whereIn('id', $localizacoesPermitidas)
            ->orderBy('nome_localizacao')
            ->get()
            ->map(function ($loc) {
                return [
                    'id' => $loc->id,
                    'nome' => $loc->nome_reduzido ?? $loc->nome_localizacao,
                ];
            });

        return response()->json(['destinos' => $destinos]);
    }

    /**
     * Motorista agenda coleta via app
     */
    public function agendar(Request $request): JsonResponse
    {
        $request->validate([
            'produto_localizacao_id' => 'required|integer|exists:produto_localizacao,id',
            'veiculo_id' => 'required|integer|exists:veiculos,id',
            'destino_localizacao_id' => 'required|integer|exists:localizacoes,id',
            'inicio_previsto_em' => 'required|date|after_or_equal:today',
            'retorno_previsto_em' => 'required|date|after:inicio_previsto_em',
            'observacao' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        // Validar destino nas localizações permitidas
        $localizacoesPermitidas = $user->getLocalizacoesPermitidasIds();
        if (!in_array((int) $request->destino_localizacao_id, $localizacoesPermitidas)) {
            return response()->json(['message' => 'Destino não permitido para você.'], 422);
        }

        $produtoLocalizacao = \App\Models\ProdutoLocalizacao::findOrFail($request->produto_localizacao_id);

        $etapaAgendamento = $this->etapaLogisticaObrigatoria(EtapaProducao::SLUG_AGENDAMENTO)
            ?? EtapaProducao::etapaInicioLogistica();
        if (!$etapaAgendamento || $produtoLocalizacao->etapa_atual_id !== $etapaAgendamento->id) {
            return response()->json(['message' => 'Produto não está disponível para agendamento logístico.'], 422);
        }

        $inicio = $request->inicio_previsto_em;
        $retorno = $request->retorno_previsto_em;

        if (ColetaLogistica::temColetaAtiva($produtoLocalizacao->id)) {
            return response()->json(['message' => 'Já existe uma coleta ativa para este produto.'], 422);
        }

        if (ColetaLogistica::temConflitoMotorista($user->id, $inicio, $retorno)) {
            return response()->json(['message' => 'Você já possui uma coleta neste horário.'], 422);
        }

        if (ColetaLogistica::temConflitoVeiculo($request->veiculo_id, $inicio, $retorno)) {
            return response()->json(['message' => 'Veículo já agendado neste horário.'], 422);
        }

        DB::beginTransaction();
        try {
            ColetaLogistica::create([
                'produto_localizacao_id' => $produtoLocalizacao->id,
                'motorista_user_id' => $user->id,
                'veiculo_id' => $request->veiculo_id,
                'destino_localizacao_id' => $request->destino_localizacao_id,
                'inicio_previsto_em' => $inicio,
                'retorno_previsto_em' => $retorno,
                'status' => ColetaLogistica::STATUS_AGENDADO,
                'observacao_motorista' => $request->observacao,
            ]);

            $this->avancarProdutoParaEtapaLogistica(
                $produtoLocalizacao,
                EtapaProducao::SLUG_AGENDAMENTO,
                $user->id,
                'Coleta agendada via app pelo motorista ' . $user->name
            );

            DB::commit();

            return response()->json(['message' => 'Coleta agendada com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao agendar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Motorista cancela coleta agendada via app
     */
    public function cancelar(Request $request, ColetaLogistica $coleta): JsonResponse
    {
        $user = $request->user();

        if ($coleta->motorista_user_id !== $user->id) {
            return response()->json(['message' => 'Você não tem permissão para cancelar esta coleta.'], 403);
        }

        if (!$coleta->podeCancelar()) {
            return response()->json(['message' => 'Esta coleta não pode ser cancelada (só é possível cancelar coletas em status agendado).'], 422);
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
                    'Coleta cancelada via app pelo motorista ' . $user->name
                );
            }

            DB::commit();
            return response()->json(['message' => 'Coleta cancelada. Produto retornou ao início da logística.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao cancelar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Registrar push subscription
     */
    public function pushSubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        if (!Schema::hasTable('push_subscriptions')) {
            return response()->json(['message' => 'Push não disponível ainda.'], 503);
        }

        PushSubscription::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'endpoint' => $request->input('endpoint'),
            ],
            [
                'p256dh_key' => $request->input('keys.p256dh'),
                'auth_key' => $request->input('keys.auth'),
            ]
        );

        return response()->json(['message' => 'Subscription registrada.']);
    }

    /**
     * Remover push subscription
     */
    public function pushUnsubscribe(Request $request): JsonResponse
    {
        if (!Schema::hasTable('push_subscriptions')) {
            return response()->json(['message' => 'OK']);
        }

        PushSubscription::where('user_id', $request->user()->id)
            ->where('endpoint', $request->input('endpoint'))
            ->delete();

        return response()->json(['message' => 'Subscription removida.']);
    }

    /**
     * Formatar coleta para resposta JSON
     */
    private function formatarColeta(ColetaLogistica $coleta, bool $detalhado = false): array
    {
        $pl = $coleta->produtoLocalizacao;

        $data = [
            'id' => $coleta->id,
            'status' => $coleta->status,
            'status_label' => ColetaLogistica::labelsStatus()[$coleta->status] ?? $coleta->status,
            'produto' => [
                'referencia' => $pl?->produto?->referencia ?? '-',
                'descricao' => $pl?->produto?->descricao ?? '-',
            ],
            'origem' => [
                'nome' => $pl?->localizacao?->nome_reduzido ?? $pl?->localizacao?->nome_localizacao ?? '-',
            ],
            'destino' => [
                'nome' => $coleta->destinoLocalizacao?->nome_reduzido ?? $coleta->destinoLocalizacao?->nome_localizacao ?? '-',
            ],
            'veiculo' => [
                'placa' => $coleta->veiculo?->placa ?? '-',
                'descricao' => $coleta->veiculo?->descricao ?? '-',
            ],
            'quantidade' => $pl?->quantidade ?? 0,
            'etapa_atual' => [
                'slug' => $pl?->etapaAtual?->slug,
                'nome' => $pl?->etapaAtual?->nome ?? '-',
            ],
            'inicio_previsto' => $coleta->inicio_previsto_em?->format('d/m/Y H:i'),
            'retorno_previsto' => $coleta->retorno_previsto_em?->format('d/m/Y H:i'),
            'chegada_origem' => $coleta->chegada_origem_em?->format('d/m/Y H:i'),
            'recebido_destino' => $coleta->recebido_destino_em?->format('d/m/Y H:i'),
        ];

        if ($detalhado) {
            $data['observacao_motorista'] = $coleta->observacao_motorista;
            $data['observacao_origem'] = $coleta->observacao_origem;
            $data['observacao_destino'] = $coleta->observacao_destino;
            $data['created_at'] = $coleta->created_at?->format('d/m/Y H:i');
        }

        return $data;
    }

    private function etapaLogisticaObrigatoria(string $slug): ?EtapaProducao
    {
        return EtapaProducao::etapaLogisticaPorSlug($slug) ?? EtapaProducao::porSlug($slug);
    }

    private function avancarProdutoParaEtapaLogistica($produtoLocalizacao, string $slug, int $userId, string $observacao): void
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
