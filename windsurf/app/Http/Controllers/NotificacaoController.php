<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificacaoService;
use App\Models\Notificacao;

class NotificacaoController extends Controller
{
    protected $notificacaoService;

    public function __construct(NotificacaoService $notificacaoService)
    {
        $this->notificacaoService = $notificacaoService;
    }

    /**
     * Exibir todas as notificações da localização do usuário
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->localizacao_id) {
            return redirect()->back()->with('error', 'Usuário não possui localização definida.');
        }

        $notificacoes = $this->notificacaoService->obterNotificacoesPorLocalizacao(
            $user->localizacao_id,
            $request->get('per_page', 15)
        );

        return view('notificacoes.index', compact('notificacoes'));
    }

    /**
     * Marcar notificação como visualizada e redirecionar para a movimentação
     */
    public function visualizar($id)
    {
        $user = auth()->user();
        
        if (!$user->localizacao_id) {
            return redirect()->back()->with('error', 'Usuário não possui localização definida.');
        }

        $notificacao = Notificacao::find($id);
        
        if (!$notificacao) {
            return redirect()->back()->with('error', 'Notificação não encontrada.');
        }

        // Verificar se o usuário pode visualizar esta notificação
        if ($notificacao->localizacao_id !== $user->localizacao_id) {
            return redirect()->back()->with('error', 'Acesso negado.');
        }

        // Marcar como visualizada se ainda não foi
        if (!$notificacao->isVisualizada()) {
            $this->notificacaoService->marcarComoVisualizada($id, $user);
        }

        // Redirecionar para a movimentação
        return redirect($notificacao->link);
    }

    /**
     * API para obter notificações não visualizadas (para o dropdown)
     */
    public function naoVisualizadas()
    {
        $user = auth()->user();
        
        if (!$user->localizacao_id) {
            return response()->json(['notificacoes' => [], 'count' => 0]);
        }

        $notificacoes = $this->notificacaoService->obterNotificacaoesNaoVisualizadas($user->localizacao_id);
        $count = $this->notificacaoService->contarNotificacaoesNaoVisualizadas($user->localizacao_id);

        return response()->json([
            'notificacoes' => $notificacoes->map(function ($notificacao) {
                return [
                    'id' => $notificacao->id,
                    'titulo' => $notificacao->titulo,
                    'mensagem' => $notificacao->mensagem,
                    'link' => route('notificacoes.visualizar', $notificacao->id),
                    'created_at' => $notificacao->created_at->diffForHumans(),
                    'tipo' => $notificacao->tipo
                ];
            }),
            'count' => $count
        ]);
    }

    /**
     * Marcar todas as notificações da localização como visualizadas
     */
    public function marcarTodasComoVisualizadas()
    {
        $user = auth()->user();
        
        if (!$user->localizacao_id) {
            return response()->json(['success' => false, 'message' => 'Usuário não possui localização definida.']);
        }

        $notificacoes = Notificacao::porLocalizacao($user->localizacao_id)
            ->naoVisualizadas()
            ->get();

        foreach ($notificacoes as $notificacao) {
            $notificacao->marcarComoVisualizada($user);
        }

        return response()->json(['success' => true, 'message' => 'Todas as notificações foram marcadas como visualizadas.']);
    }
}
