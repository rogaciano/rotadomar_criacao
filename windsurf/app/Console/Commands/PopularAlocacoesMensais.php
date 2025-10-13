<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produto;
use App\Models\ProdutoAlocacaoMensal;

class PopularAlocacoesMensais extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produtos:popular-alocacoes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popular alocações mensais para produtos existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando população de alocações mensais...');
        
        // Buscar TODOS os produtos com localização e quantidade
        try {
            $produtos = Produto::all()
                ->filter(function($produto) {
                    return $produto->localizacao_id && $produto->quantidade > 0;
                });
        } catch (\Exception $e) {
            $this->error("Erro ao buscar produtos: " . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info("📦 Encontrados {$produtos->count()} produtos para processar");
        
        $criados = 0;
        $pulados = 0;

        $bar = $this->output->createProgressBar($produtos->count());
        $bar->start();

        foreach ($produtos as $produto) {
            // Verificar se já existe alocação
            $alocacaoExistente = ProdutoAlocacaoMensal::where('produto_id', $produto->id)
                ->where('tipo', 'original')
                ->exists();

            if ($alocacaoExistente) {
                $pulados++;
                $bar->advance();
                continue;
            }

            // Definir mês/ano - usar data_prevista_faccao se existir, senão usar mês atual
            $mes = now()->month;
            $ano = now()->year;
            
            try {
                if ($produto->data_prevista_faccao) {
                    $mes = $produto->data_prevista_faccao->month;
                    $ano = $produto->data_prevista_faccao->year;
                }
            } catch (\Exception $e) {
                // Se não tem data_prevista_faccao, usa mês atual
            }
            
            // Criar alocação
            ProdutoAlocacaoMensal::create([
                'produto_id' => $produto->id,
                'localizacao_id' => $produto->localizacao_id,
                'mes' => $mes,
                'ano' => $ano,
                'quantidade' => $produto->quantidade,
                'tipo' => 'original',
                'usuario_id' => 1, // ID do primeiro usuário admin
                'observacoes' => 'Alocação inicial criada automaticamente'
            ]);

            $criados++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Processo concluído!");
        $this->info("📊 Alocações criadas: {$criados}");
        $this->info("⏭️  Produtos pulados (já tinham alocação): {$pulados}");

        return Command::SUCCESS;
    }
}
