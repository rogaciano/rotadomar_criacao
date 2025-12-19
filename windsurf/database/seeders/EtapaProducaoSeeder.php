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
            ['nome' => 'Recebimento', 'icone' => '📦', 'cor' => 'blue', 'ordem' => 1],
            ['nome' => 'Separação', 'icone' => '📋', 'cor' => 'indigo', 'ordem' => 2],
            ['nome' => 'Preparação', 'icone' => '✂️', 'cor' => 'purple', 'ordem' => 3],
            ['nome' => 'Produção', 'icone' => '⚙️', 'cor' => 'yellow', 'ordem' => 4],
            ['nome' => 'Aplicação DTF', 'icone' => '🎨', 'cor' => 'pink', 'ordem' => 5],
            ['nome' => 'Estamparia', 'icone' => '🖼️', 'cor' => 'orange', 'ordem' => 6],
            ['nome' => 'Acabamento', 'icone' => '✨', 'cor' => 'green', 'ordem' => 7],
            ['nome' => 'Aguardando Retirada', 'icone' => '📍', 'cor' => 'gray', 'ordem' => 8],
            ['nome' => 'Coletado', 'icone' => '✅', 'cor' => 'green', 'ordem' => 9],
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
            
            // Aguardando Retirada → Coletado
            ['origem' => 'Aguardando Retirada', 'destino' => 'Coletado'],
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
