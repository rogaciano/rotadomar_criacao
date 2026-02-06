<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimentacao;
use App\Models\MovimentacaoObservacao;
use Illuminate\Support\Facades\DB;

class MigrateMovimentacaoObservacoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movimentacoes:migrate-observacoes {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra observações existentes do campo movimentacoes.observacao para a tabela movimentacoes_observacoes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('Executando em modo DRY RUN - nenhuma alteração será feita.');
        }

        $this->info('Iniciando migração de observações...');

        // Buscar todas as movimentações com observações não vazias
        $movimentacoes = Movimentacao::whereNotNull('observacao')
            ->where('observacao', '!=', '')
            ->get();

        $this->info("Encontradas {$movimentacoes->count()} movimentações com observações.");

        $migrated = 0;
        $skipped = 0;

        foreach ($movimentacoes as $movimentacao) {
            // Verificar se já existem observações migradas para esta movimentação
            $existingObservacoes = MovimentacaoObservacao::where('movimentacao_id', $movimentacao->id)->count();
            
            if ($existingObservacoes > 0) {
                $this->line("Movimentação ID {$movimentacao->id} já possui observações migradas. Pulando...");
                $skipped++;
                continue;
            }

            if (!$isDryRun) {
                try {
                    DB::beginTransaction();
                    
                    // Criar nova observação com o conteúdo original
                    MovimentacaoObservacao::create([
                        'movimentacao_id' => $movimentacao->id,
                        'observacao' => $movimentacao->observacao,
                        'created_at' => $movimentacao->created_at, // Preservar data original
                        'updated_at' => $movimentacao->updated_at
                    ]);

                    // Opcional: limpar o campo original após migração
                    // Descomente a linha abaixo se quiser limpar o campo original
                    // $movimentacao->update(['observacao' => null]);

                    DB::commit();
                    $migrated++;
                    $this->info("✓ Migrada observação da movimentação ID {$movimentacao->id}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("✗ Erro ao migrar movimentação ID {$movimentacao->id}: {$e->getMessage()}");
                }
            } else {
                $migrated++;
                $this->line("[DRY RUN] Migraria observação da movimentação ID {$movimentacao->id}");
            }
        }

        $this->newLine();
        $this->info("Migração concluída!");
        $this->info("- Observações migradas: {$migrated}");
        $this->info("- Observações puladas (já migradas): {$skipped}");

        if ($isDryRun) {
            $this->warn('Nenhuma alteração foi feita. Execute sem --dry-run para aplicar as mudanças.');
        }

        return Command::SUCCESS;
    }
}
