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

        // Criar etapas — contexto localizacao (facção) vs logistica
        $etapas = [
            ['nome' => 'Recebimento', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '📦', 'cor' => 'blue', 'ordem' => 1],
            ['nome' => 'Separação', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '📋', 'cor' => 'indigo', 'ordem' => 2],
            ['nome' => 'Preparação', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '✂️', 'cor' => 'purple', 'ordem' => 3],
            ['nome' => 'Produção', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '⚙️', 'cor' => 'yellow', 'ordem' => 4],
            ['nome' => 'Aplicação DTF', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '🎨', 'cor' => 'pink', 'ordem' => 5],
            ['nome' => 'Estamparia', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '🖼️', 'cor' => 'orange', 'ordem' => 6],
            ['nome' => 'Acabamento', 'slug' => null, 'contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO, 'inicia_logistica' => false, 'icone' => '✨', 'cor' => 'green', 'ordem' => 7],
            ['nome' => 'Agendamento', 'slug' => EtapaProducao::SLUG_AGENDAMENTO, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => true, 'icone' => '�️', 'cor' => 'gray', 'ordem' => 1],
            ['nome' => 'Saída da Fábrica / Solicitar Retirada', 'slug' => EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => false, 'icone' => '📤', 'cor' => 'yellow', 'ordem' => 2],
            ['nome' => 'Retirada Confirmada pela Facção', 'slug' => EtapaProducao::SLUG_RETIRADA_CONFIRMADA_FACCAO, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => false, 'icone' => '�', 'cor' => 'orange', 'ordem' => 3],
            ['nome' => 'Em Trânsito', 'slug' => EtapaProducao::SLUG_EM_TRANSITO, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => false, 'icone' => '🚚', 'cor' => 'orange', 'ordem' => 4],
            ['nome' => 'Entrega Confirmada na Fábrica', 'slug' => EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => false, 'icone' => '🏭', 'cor' => 'indigo', 'ordem' => 5],
            ['nome' => 'Check-in', 'slug' => EtapaProducao::SLUG_CHECK_IN, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => false, 'icone' => '🧾', 'cor' => 'blue', 'ordem' => 6],
            ['nome' => 'Chegada do Produto na Fábrica', 'slug' => EtapaProducao::SLUG_CHEGADA_PRODUTO_FABRICA, 'contexto' => EtapaProducao::CONTEXTO_LOGISTICA, 'inicia_logistica' => false, 'icone' => '✅', 'cor' => 'green', 'ordem' => 7],
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

            // Acabamento → Agendamento
            ['origem' => 'Acabamento', 'destino' => 'Agendamento'],

            // Fluxo logístico detalhado
            ['origem' => 'Agendamento', 'destino' => 'Saída da Fábrica / Solicitar Retirada', 'label' => 'Solicitar retirada', 'cor' => 'yellow'],
            ['origem' => 'Saída da Fábrica / Solicitar Retirada', 'destino' => 'Retirada Confirmada pela Facção', 'label' => 'Confirmar retirada', 'cor' => 'orange'],
            ['origem' => 'Retirada Confirmada pela Facção', 'destino' => 'Em Trânsito', 'label' => 'Em trânsito', 'cor' => 'orange'],
            ['origem' => 'Em Trânsito', 'destino' => 'Entrega Confirmada na Fábrica', 'label' => 'Confirmar entrega', 'cor' => 'indigo'],
            ['origem' => 'Entrega Confirmada na Fábrica', 'destino' => 'Check-in', 'label' => 'Registrar check-in', 'cor' => 'blue'],
            ['origem' => 'Check-in', 'destino' => 'Chegada do Produto na Fábrica', 'label' => 'Confirmar chegada', 'cor' => 'green'],
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
