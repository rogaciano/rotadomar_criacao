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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
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
     * Motorista confirma chegada na origem → EM TRANSITO
     */
    public function confirmarChegada(Request $request, ColetaLogistica $coleta): JsonResponse
    {
        $user = $request->user();

        if ($coleta->motorista_user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        if ($coleta->status !== ColetaLogistica::STATUS_AGENDADO) {
            return response()->json(['message' => 'Esta coleta não está em status agendado.'], 422);
        }

        $request->validate([
            'observacao' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $coleta->update([
                'status' => ColetaLogistica::STATUS_EM_TRANSITO,
                'chegada_origem_em' => now(),
                'observacao_motorista' => $request->input('observacao'),
            ]);

            $etapaEmTransito = EtapaProducao::porSlug(EtapaProducao::SLUG_EM_TRANSITO);
            if ($etapaEmTransito) {
                $coleta->produtoLocalizacao->avancarEtapa(
                    $etapaEmTransito->id,
                    $user->id,
                    'Motorista confirmou chegada na origem via app'
                );
            }

            DB::commit();

            $coleta->refresh();
            $coleta->load([
                'produtoLocalizacao.produto',
                'produtoLocalizacao.localizacao',
                'veiculo',
                'destinoLocalizacao',
            ]);

            return response()->json([
                'message' => 'Chegada confirmada! Produto em trânsito.',
                'coleta' => $this->formatarColeta($coleta),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao confirmar chegada: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Motorista confirma entrega no destino → FINALIZADO
     */
    public function confirmarEntrega(Request $request, ColetaLogistica $coleta): JsonResponse
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
                'status' => ColetaLogistica::STATUS_FINALIZADO,
                'recebido_destino_em' => now(),
                'observacao_destino' => $request->input('observacao'),
            ]);

            $etapaColetado = EtapaProducao::porSlug(EtapaProducao::SLUG_COLETADO);
            if ($etapaColetado) {
                $coleta->produtoLocalizacao->avancarEtapa(
                    $etapaColetado->id,
                    $user->id,
                    'Motorista confirmou entrega no destino via app'
                );
            }

            DB::commit();

            $coleta->refresh();
            $coleta->load([
                'produtoLocalizacao.produto',
                'produtoLocalizacao.localizacao',
                'veiculo',
                'destinoLocalizacao',
            ]);

            return response()->json([
                'message' => 'Entrega confirmada! Coleta finalizada.',
                'coleta' => $this->formatarColeta($coleta),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao confirmar entrega: ' . $e->getMessage()], 500);
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
            'inicio_previsto' => $coleta->inicio_previsto_em?->format('d/m/Y H:i'),
            'retorno_previsto' => $coleta->retorno_previsto_em?->format('d/m/Y H:i'),
            'chegada_origem' => $coleta->chegada_origem_em?->format('d/m/Y H:i'),
            'recebido_destino' => $coleta->recebido_destino_em?->format('d/m/Y H:i'),
        ];

        if ($detalhado) {
            $data['observacao_motorista'] = $coleta->observacao_motorista;
            $data['observacao_origem'] = $coleta->observacao_origem;
            $data['observacao_destino'] = $coleta->observacao_destino;
            $data['etapa_atual'] = $pl?->etapaAtual?->nome ?? '-';
            $data['created_at'] = $coleta->created_at?->format('d/m/Y H:i');
        }

        return $data;
    }
}
