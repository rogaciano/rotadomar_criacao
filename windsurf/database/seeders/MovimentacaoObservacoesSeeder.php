<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movimentacao;
use App\Models\MovimentacaoObservacao;
use Carbon\Carbon;

class MovimentacaoObservacoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Exemplo de observações para teste
        $observacoesExemplo = [
            'Produto recebido em perfeitas condições.',
            'Aguardando aprovação do cliente.',
            'Cliente solicitou alteração no acabamento.',
            'Alteração realizada conforme solicitado.',
            'Produto finalizado e pronto para entrega.',
            'Entregue ao cliente com sucesso.'
        ];

        // Pegar algumas movimentações aleatórias para adicionar observações de teste
        $movimentacoes = Movimentacao::inRandomOrder()->limit(5)->get();

        foreach ($movimentacoes as $movimentacao) {
            // Adicionar entre 2 a 4 observações por movimentação
            $numObservacoes = rand(2, 4);
            $baseDate = Carbon::now()->subDays(30);

            for ($i = 0; $i < $numObservacoes; $i++) {
                MovimentacaoObservacao::create([
                    'movimentacao_id' => $movimentacao->id,
                    'observacao' => $observacoesExemplo[array_rand($observacoesExemplo)],
                    'created_at' => $baseDate->copy()->addDays($i * 7),
                    'updated_at' => $baseDate->copy()->addDays($i * 7)
                ]);
            }

            echo "Adicionadas {$numObservacoes} observações para a movimentação ID {$movimentacao->id}\n";
        }

        echo "\nSeeder de observações executado com sucesso!\n";
    }
}
