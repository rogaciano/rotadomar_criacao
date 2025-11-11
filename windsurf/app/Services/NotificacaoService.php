<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\Movimentacao;
use App\Models\User;

class NotificacaoService
{
    /**
     * Criar notificação para nova movimentação
     */
    public function criarNotificacaoNovaMovimentacao(Movimentacao $movimentacao): Notificacao
    {
        $produto = $movimentacao->produto;
        $localizacao = $movimentacao->localizacao;
        
        return Notificacao::create([
            'movimentacao_id' => $movimentacao->id,
            'localizacao_id' => $movimentacao->localizacao_id,
            'tipo' => 'nova_movimentacao',
            'titulo' => 'Nova Movimentação Criada',
            'mensagem' => "Nova movimentação criada para {$produto->referencia} na {$localizacao->nome_localizacao}",
            'link' => route('movimentacoes.show', $movimentacao->id)
        ]);
    }

    /**
     * Criar notificação para movimentação concluída
     */
    public function criarNotificacaoMovimentacaoConcluida(Movimentacao $movimentacao): Notificacao
    {
        $produto = $movimentacao->produto;
        $localizacao = $movimentacao->localizacao;
        
        return Notificacao::create([
            'movimentacao_id' => $movimentacao->id,
            'localizacao_id' => $movimentacao->localizacao_id,
            'tipo' => 'movimentacao_concluida',
            'titulo' => 'Movimentação Concluída',
            'mensagem' => "Movimentação de {$produto->referencia} foi concluída na {$localizacao->nome_localizacao}",
            'link' => route('movimentacoes.show', $movimentacao->id)
        ]);
    }

    /**
     * Obter notificações não visualizadas para uma localização
     */
    public function obterNotificacaoesNaoVisualizadas(int $localizacaoId, bool $podeVerTodas = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = Notificacao::with(['movimentacao.produto', 'localizacao'])
            ->naoVisualizadas();
        
        // Se a localização não pode ver todas, filtrar apenas pela sua localização
        if (!$podeVerTodas) {
            $query->porLocalizacao($localizacaoId);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Obter todas as notificações para uma localização com paginação
     */
    public function obterNotificacoesPorLocalizacao(int $localizacaoId, int $perPage = 15, bool $podeVerTodas = false)
    {
        $query = Notificacao::with(['movimentacao.produto', 'localizacao', 'visualizadaPor']);
        
        // Se a localização não pode ver todas, filtrar apenas pela sua localização
        if (!$podeVerTodas) {
            $query->porLocalizacao($localizacaoId);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Marcar notificação como visualizada
     */
    public function marcarComoVisualizada(int $notificacaoId, User $user): bool
    {
        $notificacao = Notificacao::find($notificacaoId);
        
        if (!$notificacao || $notificacao->isVisualizada()) {
            return false;
        }

        // Verificar se o usuário pertence à mesma localização da notificação
        if ($user->localizacao_id !== $notificacao->localizacao_id) {
            return false;
        }

        $notificacao->marcarComoVisualizada($user);
        return true;
    }

    /**
     * Contar notificações não visualizadas para uma localização
     */
    public function contarNotificacaoesNaoVisualizadas(int $localizacaoId, bool $podeVerTodas = false): int
    {
        $query = Notificacao::naoVisualizadas();
        
        // Se a localização não pode ver todas, filtrar apenas pela sua localização
        if (!$podeVerTodas) {
            $query->porLocalizacao($localizacaoId);
        }
        
        return $query->count();
    }

    /**
     * Limpar notificações antigas (mais de 30 dias)
     */
    public function limparNotificacaoesAntigas(): int
    {
        return Notificacao::where('created_at', '<', now()->subDays(30))->delete();
    }
}
