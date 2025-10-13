<?php

namespace App\Observers;

use App\Models\Produto;
use App\Models\ProdutoAlocacaoMensal;

class ProdutoObserver
{
    /**
     * Handle the Produto "created" event.
     */
    public function created(Produto $produto): void
    {
        $this->criarAlocacaoInicial($produto);
    }

    /**
     * Handle the Produto "updated" event.
     */
    public function updated(Produto $produto): void
    {
        // Se mudou a data prevista de facção ou localização, atualiza alocação
        if ($produto->isDirty(['data_prevista_faccao', 'localizacao_id', 'quantidade'])) {
            $this->atualizarAlocacao($produto);
        }
    }

    /**
     * Criar alocação inicial ao criar produto
     */
    private function criarAlocacaoInicial(Produto $produto)
    {
        if ($produto->data_prevista_faccao && $produto->localizacao_id && $produto->quantidade > 0) {
            ProdutoAlocacaoMensal::create([
                'produto_id' => $produto->id,
                'localizacao_id' => $produto->localizacao_id,
                'mes' => $produto->data_prevista_faccao->month,
                'ano' => $produto->data_prevista_faccao->year,
                'quantidade' => $produto->quantidade,
                'tipo' => 'original',
                'usuario_id' => auth()->id(),
                'observacoes' => 'Alocação inicial do produto'
            ]);
        }
    }

    /**
     * Atualizar alocação ao atualizar produto
     */
    private function atualizarAlocacao(Produto $produto)
    {
        if (!$produto->data_prevista_faccao || !$produto->localizacao_id) {
            return;
        }

        // Buscar alocação original (tipo = 'original')
        $alocacaoOriginal = ProdutoAlocacaoMensal::where('produto_id', $produto->id)
            ->where('tipo', 'original')
            ->first();

        if ($alocacaoOriginal) {
            // Atualizar alocação existente
            $alocacaoOriginal->update([
                'localizacao_id' => $produto->localizacao_id,
                'mes' => $produto->data_prevista_faccao->month,
                'ano' => $produto->data_prevista_faccao->year,
                'quantidade' => $produto->quantidade,
                'observacoes' => 'Atualizado em ' . now()->format('d/m/Y H:i')
            ]);
        } else {
            // Criar nova alocação se não existir
            $this->criarAlocacaoInicial($produto);
        }
    }

    /**
     * Handle the Produto "deleted" event.
     */
    public function deleted(Produto $produto): void
    {
        // Soft delete das alocações
        ProdutoAlocacaoMensal::where('produto_id', $produto->id)->delete();
    }

    /**
     * Handle the Produto "forceDeleted" event.
     */
    public function forceDeleted(Produto $produto): void
    {
        // Delete permanente das alocações
        ProdutoAlocacaoMensal::where('produto_id', $produto->id)->forceDelete();
    }
}
