<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Localizacao;
use App\Models\ProdutoLocalizacao;
use Illuminate\Http\Request;

class ProdutoLocalizacaoController extends Controller
{
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
            'concluido' => 'nullable|boolean'
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
            'concluido' => $request->has('concluido') ? 1 : 0
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
            'concluido' => 'nullable|boolean'
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
            'concluido' => $request->has('concluido') ? 1 : 0
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

        return redirect()->route('produtos.show', $produtoId)
            ->with('success', 'Localização removida com sucesso!');
    }
}
