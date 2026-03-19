<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EtapaProducao;
use App\Models\EtapaTransicao;

class EtapaProducaoSeeder extends Seeder
{
    /**
     * Seed das etapas de produção padrão
     */
    public function run(): void
    {
        // Limpar dados existentes
        EtapaTransicao::query()->delete();
        EtapaProducao::query()->forceDelete();

        // Criar etapas
        $etapas = [
            ['nome' => 'Recebimento', 'slug' => null, 'icone' => '📦', 'cor' => 'blue', 'ordem' => 1],
            ['nome' => 'Separação', 'slug' => null, 'icone' => '📋', 'cor' => 'indigo', 'ordem' => 2],
            ['nome' => 'Preparação', 'slug' => null, 'icone' => '✂️', 'cor' => 'purple', 'ordem' => 3],
            ['nome' => 'Produção', 'slug' => null, 'icone' => '⚙️', 'cor' => 'yellow', 'ordem' => 4],
            ['nome' => 'Aplicação DTF', 'slug' => null, 'icone' => '🎨', 'cor' => 'pink', 'ordem' => 5],
            ['nome' => 'Estamparia', 'slug' => null, 'icone' => '🖼️', 'cor' => 'orange', 'ordem' => 6],
            ['nome' => 'Acabamento', 'slug' => null, 'icone' => '✨', 'cor' => 'green', 'ordem' => 7],
            ['nome' => 'Aguardando Retirada', 'slug' => 'aguardando_retirada', 'icone' => '📍', 'cor' => 'gray', 'ordem' => 8],
            ['nome' => 'Aguardando Motorista', 'slug' => 'aguardando_motorista', 'icone' => '🚛', 'cor' => 'yellow', 'ordem' => 9],
            ['nome' => 'Em Trânsito', 'slug' => 'em_transito', 'icone' => '🚚', 'cor' => 'orange', 'ordem' => 10],
            ['nome' => 'Coletado', 'slug' => 'coletado', 'icone' => '✅', 'cor' => 'green', 'ordem' => 11],
        ];

        $etapasCriadas = [];
        foreach ($etapas as $etapa) {
            $etapasCriadas[$etapa['nome']] = EtapaProducao::create($etapa);
        }

        // Criar transições
        $transicoes = [
            // Recebimento → Separação
            ['origem' => 'Recebimento', 'destino' => 'Separação'],

            // Separação → Preparação
            ['origem' => 'Separação', 'destino' => 'Preparação'],

            // Preparação → Produção
            ['origem' => 'Preparação', 'destino' => 'Produção'],

            // Produção → (bifurcação para 3 caminhos)
            ['origem' => 'Produção', 'destino' => 'Acabamento', 'label' => 'Sem DTF/Estampa'],
            ['origem' => 'Produção', 'destino' => 'Aplicação DTF', 'label' => 'Aplicar DTF', 'cor' => 'pink'],
            ['origem' => 'Produção', 'destino' => 'Estamparia', 'label' => 'Enviar Estampa', 'cor' => 'orange'],

            // DTF → Acabamento
            ['origem' => 'Aplicação DTF', 'destino' => 'Acabamento'],

            // Estamparia → Acabamento
            ['origem' => 'Estamparia', 'destino' => 'Acabamento'],

            // Acabamento → Aguardando Retirada
            ['origem' => 'Acabamento', 'destino' => 'Aguardando Retirada'],

            // Fluxo logístico: Aguardando Retirada → Aguardando Motorista → Em Trânsito → Coletado
            ['origem' => 'Aguardando Retirada', 'destino' => 'Aguardando Motorista', 'cor' => 'yellow'],
            ['origem' => 'Aguardando Motorista', 'destino' => 'Em Trânsito', 'cor' => 'orange'],
            ['origem' => 'Em Trânsito', 'destino' => 'Coletado', 'cor' => 'green'],
        ];

        $ordem = 0;
        foreach ($transicoes as $t) {
            EtapaTransicao::create([
                'etapa_origem_id' => $etapasCriadas[$t['origem']]->id,
                'etapa_destino_id' => $etapasCriadas[$t['destino']]->id,
                'label_botao' => $t['label'] ?? null,
                'cor_botao' => $t['cor'] ?? 'blue',
                'ativo' => true,
                'ordem' => $ordem++
            ]);
        }

        $this->command->info('Etapas de produção e transições criadas com sucesso!');
    }
}
