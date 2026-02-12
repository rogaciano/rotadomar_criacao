<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Localizacao;
use App\Models\ProdutoLocalizacao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdutoLocalizacaoController extends Controller
{
    protected $notificacaoService;

    public function __construct(\App\Services\NotificacaoService $notificacaoService)
    {
        $this->notificacaoService = $notificacaoService;
    }
    /**
     * Adicionar uma localização ao produto
     */
    public function store(Request $request, $produtoId)
    {
        if (!auth()->user()->canCreate('produto_localizacao')) {
            abort(403);
        }

        $request->validate([
            'localizacao_id' => 'required|exists:localizacoes,id',
            'quantidade' => 'required|integer|min:1',
            'data_prevista_faccao' => 'nullable|date',
            'data_envio_faccao' => 'nullable|date',
            'data_retorno_faccao' => 'nullable|date|required_if:concluido,1',
            'ordem_producao' => 'required|string|max:30',
            'observacao' => 'nullable|string|max:255',
            'concluido' => 'nullable|boolean',
            'data_entrega_faccao' => 'nullable|date'
        ]);

        $produto = Produto::findOrFail($produtoId);

        $localizacao = Localizacao::find($request->localizacao_id);

        // Verificar se já existe essa ordem de produção para este produto e localização
        $existe = ProdutoLocalizacao::where('produto_id', $produtoId)
            ->where('localizacao_id', $request->localizacao_id)
            ->where('ordem_producao', $request->ordem_producao)
            ->first();

        if ($existe) {
            return redirect()->route('produtos.show', $produtoId)
                ->with('error', "Já existe um registro com a Ordem de Produção '{$request->ordem_producao}' para esta localização. Use a opção 'Editar' para alterar.");
        }

        // Criar a associação
        $produtoLocalizacao = ProdutoLocalizacao::create([
            'produto_id' => $produtoId,
            'localizacao_id' => $request->localizacao_id,
            'quantidade' => $request->quantidade,
            'data_prevista_faccao' => $request->data_prevista_faccao,
            'data_envio_faccao' => $request->data_envio_faccao,
            'data_retorno_faccao' => $request->data_retorno_faccao,
            'ordem_producao' => $request->ordem_producao,
            'observacao' => $request->observacao,
            'concluido' => $request->has('concluido') ? 1 : 0,
            'data_entrega_faccao' => $request->data_entrega_faccao
        ]);

        activity('produtos')
            ->causedBy(auth()->user())
            ->performedOn($produto)
            ->withProperties([
                'action' => 'produto_localizacao_created',
                'produto_id' => $produto->id,
                'produto_referencia' => $produto->referencia,
                'produto_localizacao_id' => $produtoLocalizacao->id,
                'localizacao_id' => $request->localizacao_id,
                'localizacao_nome' => $localizacao?->nome_localizacao,
                'ordem_producao' => $request->ordem_producao,
                'quantidade' => (int) $request->quantidade,
                'data_prevista_faccao' => $request->data_prevista_faccao,
                'data_envio_faccao' => $request->data_envio_faccao,
                'data_retorno_faccao' => $request->data_retorno_faccao,
                'concluido' => $request->has('concluido') ? 1 : 0,
            ])
            ->event('created')
            ->log('Localizaçao com OP e quantidade cadastrada no produto');

        // Notificar usuários da localização destino
        try {
            $this->notificacaoService->criarNotificacaoAtribuicaoLocalizacao($produtoLocalizacao);
        } catch (\Exception $e) {
            \Log::error("Erro ao criar notificação de atribuição: " . $e->getMessage());
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Localização adicionada com sucesso!');
    }

    /**
     * Atualizar uma localização do produto
     */
    public function update(Request $request, $produtoId, $produtoLocalizacaoId)
    {
        if (!auth()->user()->canUpdate('produto_localizacao')) {
            abort(403);
        }

        $request->validate([
            'quantidade' => 'required|integer|min:1',
            'data_prevista_faccao' => 'nullable|date',
            'data_envio_faccao' => 'nullable|date',
            'data_retorno_faccao' => 'nullable|date|required_if:concluido,1',
            'ordem_producao' => 'required|string|max:30',
            'observacao' => 'nullable|string|max:255',
            'concluido' => 'nullable|boolean',
            'data_entrega_faccao' => 'nullable|date'
        ]);

        // Buscar o registro na tabela pivot pelo ID
        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        $produto = Produto::findOrFail($produtoId);
        $localizacao = Localizacao::find($produtoLocalizacao->localizacao_id);
        $before = $produtoLocalizacao->getOriginal();

        // Verificar se a nova ordem de produção cria duplicata com outro registro
        $existe = ProdutoLocalizacao::where('produto_id', $produtoId)
            ->where('localizacao_id', $produtoLocalizacao->localizacao_id)
            ->where('ordem_producao', $request->ordem_producao)
            ->where('id', '!=', $produtoLocalizacaoId) // Excluir o registro atual
            ->first();

        if ($existe) {
            return redirect()->route('produtos.show', $produtoId)
                ->with('error', "Já existe outro registro com a Ordem de Produção '{$request->ordem_producao}' para esta localização.");
        }

        // Atualizar os dados
        $produtoLocalizacao->update([
            'quantidade' => $request->quantidade,
            'data_prevista_faccao' => $request->data_prevista_faccao,
            'data_envio_faccao' => $request->data_envio_faccao,
            'data_retorno_faccao' => $request->data_retorno_faccao,
            'ordem_producao' => $request->ordem_producao,
            'observacao' => $request->observacao,
            'concluido' => $request->has('concluido') ? 1 : 0,
            'data_entrega_faccao' => $request->data_entrega_faccao
        ]);

        activity('produtos')
            ->causedBy(auth()->user())
            ->performedOn($produto)
            ->withProperties([
                'action' => 'produto_localizacao_updated',
                'produto_id' => $produto->id,
                'produto_referencia' => $produto->referencia,
                'produto_localizacao_id' => $produtoLocalizacao->id,
                'localizacao_id' => $produtoLocalizacao->localizacao_id,
                'localizacao_nome' => $localizacao?->nome_localizacao,
                'before' => $before,
                'after' => $produtoLocalizacao->getAttributes(),
            ])
            ->event('updated')
            ->log('Movimentação atualizada no produto');

        // Notificar alteração
        try {
            $this->notificacaoService->criarNotificacaoAlteracaoAtribuicaoLocalizacao($produtoLocalizacao);
        } catch (\Exception $e) {
            \Log::error("Erro ao criar notificação de alteração de atribuição: " . $e->getMessage());
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Localização atualizada com sucesso!');
    }

    /**
     * Remover uma localização do produto
     */
    public function destroy($produtoId, $produtoLocalizacaoId)
    {
        if (!auth()->user()->canDelete('produto_localizacao')) {
            abort(403);
        }

        // Buscar o registro na tabela pivot pelo ID
        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        $produto = Produto::findOrFail($produtoId);
        $localizacao = Localizacao::find($produtoLocalizacao->localizacao_id);
        $snapshot = $produtoLocalizacao->getAttributes();

        // Guardar ordem de produção para log
        $ordemProducao = $produtoLocalizacao->ordem_producao;
        $localizacaoId = $produtoLocalizacao->localizacao_id;

        // Deletar o registro (isso vai disparar o Observer automaticamente)
        $produtoLocalizacao->delete();

        activity('produtos')
            ->causedBy(auth()->user())
            ->performedOn($produto)
            ->withProperties([
                'action' => 'produto_localizacao_deleted',
                'produto_id' => $produto->id,
                'produto_referencia' => $produto->referencia,
                'produto_localizacao_id' => $produtoLocalizacaoId,
                'localizacao_id' => $localizacaoId,
                'localizacao_nome' => $localizacao?->nome_localizacao,
                'ordem_producao' => $ordemProducao,
                'snapshot' => $snapshot,
            ])
            ->event('deleted')
            ->log("Localização  $localizacao?->nome_localizacao da OP $ordemProducao removida do produto com Ref. {$produto->referencia}");

        // Log para debug
        \Log::info("Localização removida do produto", [
            'produto_id' => $produtoId,
            'localizacao_id' => $localizacaoId,
            'ordem_producao' => $ordemProducao,
            'pivot_id' => $produtoLocalizacaoId,
            'user_id' => auth()->id()
        ]);

        // Notificar remoção
        try {
            if ($localizacao) {
                $this->notificacaoService->criarNotificacaoRemocaoAtribuicaoLocalizacao($snapshot, $localizacao, $produto);
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao criar notificação de remoção de atribuição: " . $e->getMessage());
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Localização removida com sucesso!');
    }

    /**
     * Avançar para uma nova etapa de produção
     */
    public function avancarEtapa(Request $request, $produtoId, $produtoLocalizacaoId)
    {
        $request->validate([
            'etapa_id' => 'required|exists:etapas_producao,id',
            'observacao' => 'nullable|string|max:255'
        ]);

        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        // Verificar autorização
        $user = auth()->user();
        $podeGerenciar = $user->isAdmin() || (
            $user->localizacao_id == $produtoLocalizacao->localizacao_id
        );

        if (!$podeGerenciar) {
            return redirect()->back()->with('error', 'Você não tem permissão para gerenciar a etapa desta localização.');
        }

        // Buscar a etapa destino para verificar se obriga data_entrega_faccao
        $etapaDestino = \App\Models\EtapaProducao::find($request->etapa_id);

        // Validação: Apenas se a etapa destino obriga data_entrega_faccao
        if ($etapaDestino && $etapaDestino->obriga_data_entrega_faccao && !$produtoLocalizacao->data_entrega_faccao) {
            return redirect()->back()->with('alert_error', 'Para avançar para esta etapa, você deve primeiro preencher a "Entrega Prevista Facção".');
        }

        $produtoLocalizacao->avancarEtapa(
            $request->etapa_id,
            auth()->id(),
            $request->observacao
        );

        // Notificar setor
        $this->notificacaoService->criarNotificacaoMudancaEtapa($produtoLocalizacao);

        if ($request->has('back_url') && Str::startsWith($request->back_url, ['/', url('/')])) {
            return redirect($request->back_url)->with('success', 'Etapa avançada com sucesso!');
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Etapa avançada com sucesso!');
    }

    /**
     * Voltar para a etapa anterior
     */
    public function voltarEtapa(Request $request, $produtoId, $produtoLocalizacaoId)
    {
        $request->validate([
            'observacao' => 'nullable|string|max:255'
        ]);

        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        // Verificar autorização
        $user = auth()->user();
        $podeGerenciar = $user->isAdmin() || (
            $user->localizacao_id == $produtoLocalizacao->localizacao_id
        );

        if (!$podeGerenciar) {
            return redirect()->back()->with('error', 'Você não tem permissão para gerenciar a etapa desta localização.');
        }

        if (!$produtoLocalizacao->etapa_anterior_id) {
            return redirect()->route('produtos.show', $produtoId)
                ->with('error', 'Não há etapa anterior para voltar.');
        }

        $produtoLocalizacao->voltarEtapa(
            auth()->id(),
            $request->observacao
        );

        // Notificar setor
        $this->notificacaoService->criarNotificacaoMudancaEtapa($produtoLocalizacao);

        if ($request->has('back_url') && Str::startsWith($request->back_url, ['/', url('/')])) {
            return redirect($request->back_url)->with('success', 'Etapa revertida com sucesso!');
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Etapa revertida com sucesso!');
    }

    /**
     * Limpar a etapa atual (resetar para Não Definida)
     */
    public function limparEtapa(Request $request, $produtoId, $produtoLocalizacaoId)
    {
        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        // Verificar autorização
        $user = auth()->user();
        $podeGerenciar = $user->isAdmin() || (
            $user->localizacao_id == $produtoLocalizacao->localizacao_id
        );

        if (!$podeGerenciar) {
            return redirect()->back()->with('error', 'Você não tem permissão para gerenciar a etapa desta localização.');
        }

        // Limpar os campos de etapa
        $produtoLocalizacao->etapa_atual_id = null;
        $produtoLocalizacao->etapa_anterior_id = null;
        $produtoLocalizacao->save();

        // Registrar no log de atividades
        activity('produtos')
            ->causedBy(auth()->user())
            ->performedOn(Produto::find($produtoId))
            ->withProperties([
                'action' => 'produto_localizacao_etapa_cleared',
                'produto_id' => $produtoId,
                'produto_localizacao_id' => $produtoLocalizacaoId,
            ])
            ->log('Etapa da localização limpa (resetada)');

        if ($request->has('back_url') && Str::startsWith($request->back_url, ['/', url('/')])) {
            return redirect($request->back_url)->with('success', 'Etapa limpa com sucesso!');
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Etapa limpa com sucesso!');
    }

    /**
     * Definir etapa inicial
     */
    public function definirEtapa(Request $request, $produtoId, $produtoLocalizacaoId)
    {
        $request->validate([
            'etapa_id' => 'required|exists:etapas_producao,id',
            'observacao' => 'nullable|string|max:255'
        ]);

        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        // Verificar autorização
        $user = auth()->user();
        $podeGerenciar = $user->isAdmin() || (
            $user->localizacao_id == $produtoLocalizacao->localizacao_id
        );

        if (!$podeGerenciar) {
            return redirect()->back()->with('error', 'Você não tem permissão para gerenciar a etapa desta localização.');
        }

        // Buscar a etapa para verificar se obriga data_entrega_faccao
        $etapa = \App\Models\EtapaProducao::find($request->etapa_id);

        // Validação: Apenas se a etapa obriga data_entrega_faccao
        if ($etapa && $etapa->obriga_data_entrega_faccao && !$produtoLocalizacao->data_entrega_faccao) {
            return redirect()->back()->with('alert_error', 'Para definir esta etapa, você deve primeiro preencher a "Entrega Prevista Facção".');
        }

        $produtoLocalizacao->definirEtapaInicial(
            $request->etapa_id,
            auth()->id(),
            $request->observacao
        );

        // Notificar setor
        $this->notificacaoService->criarNotificacaoMudancaEtapa($produtoLocalizacao);

        if ($request->has('back_url') && Str::startsWith($request->back_url, ['/', url('/')])) {
            return redirect($request->back_url)->with('success', 'Etapa definida com sucesso!');
        }

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Etapa definida com sucesso!');
    }

    /**
     * Ver histórico de etapas
     */
    public function historicoEtapas($produtoId, $produtoLocalizacaoId)
    {
        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->with(['produto', 'localizacao'])
            ->firstOrFail();

        $historico = $produtoLocalizacao->historicoEtapas()
            ->reorder('created_at', 'asc')
            ->with(['etapaAnterior', 'etapaNova', 'usuario'])
            ->get();

        return view('produtos.historico-etapas', compact('produtoLocalizacao', 'historico'));
    }

    /**
     * Atualizar a data de entrega da facção
     */
    public function updateDataEntrega(Request $request, $produtoId, $produtoLocalizacaoId)
    {
        $request->validate([
            'data_entrega_faccao' => 'nullable|date'
        ]);

        $user = auth()->user();
        $produtoLocalizacao = ProdutoLocalizacao::where('id', $produtoLocalizacaoId)
            ->where('produto_id', $produtoId)
            ->firstOrFail();

        // Verificar se o usuário está logado em uma localização com capacidade > 0
        // E se é a mesma localização do registro ou se é admin
        $localizacaoUsuario = $user->localizacao;

        $podeEditar = $user->isAdmin() || (
            $localizacaoUsuario &&
            $localizacaoUsuario->capacidade > 0 &&
            $localizacaoUsuario->id == $produtoLocalizacao->localizacao_id
        );

        if (!$podeEditar) {
            return redirect()->back()->with('error', 'Você não tem permissão para editar esta data de entrega.');
        }

        $produtoLocalizacao->update([
            'data_entrega_faccao' => $request->data_entrega_faccao
        ]);

        // Notificar alteração de data de entrega
        try {
            $this->notificacaoService->criarNotificacaoAlteracaoAtribuicaoLocalizacao($produtoLocalizacao);
        } catch (\Exception $e) {
            \Log::error("Erro ao criar notificação de alteração de data de entrega: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data de entrega atualizada com sucesso!');
    }

    /**
     * Atualizar o histórico de etapas (data/hora)
     */
    public function updateHistoricoEtapa(Request $request, $historicoId)
    {
        $request->validate([
            'created_at' => 'required|date'
        ]);

        $historico = \App\Models\ProdutoLocalizacaoHistoricoEtapa::findOrFail($historicoId);

        // Verificar permissão
        if (!auth()->user()->isAdmin() && !auth()->user()->canUpdate('produto_localizacao_historico_etapas')) {
             abort(403);
        }

        $historico->created_at = $request->created_at;
        $historico->updated_by_user_id = auth()->id();
        $historico->save();

        return redirect()->back()->with('success', 'Data e hora atualizadas com sucesso!');
    }
}
