<?php

use App\Models\EtapaProducao;
use App\Models\EtapaTransicao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Garante transição de handoff: última etapa de produção → início da logística.
     */
    public function up(): void
    {
        if (!Schema::hasTable('etapas_producao') || !Schema::hasTable('etapas_transicoes')) {
            return;
        }

        $acabamento = EtapaProducao::query()
            ->where('contexto', EtapaProducao::CONTEXTO_LOCALIZACAO)
            ->where('nome', 'Acabamento')
            ->first();

        $inicioLogistica = EtapaProducao::etapaInicioLogistica();

        if (!$acabamento || !$inicioLogistica) {
            return;
        }

        $jaExiste = EtapaTransicao::query()
            ->where('etapa_origem_id', $acabamento->id)
            ->where('etapa_destino_id', $inicioLogistica->id)
            ->exists();

        if ($jaExiste) {
            return;
        }

        $maxOrdem = EtapaTransicao::query()
            ->where('etapa_origem_id', $acabamento->id)
            ->max('ordem');

        EtapaTransicao::create([
            'etapa_origem_id' => $acabamento->id,
            'etapa_destino_id' => $inicioLogistica->id,
            'label_botao' => 'Enviar para logística',
            'cor_botao' => 'gray',
            'ativo' => true,
            'ordem' => ($maxOrdem ?? -1) + 1,
        ]);
    }

    public function down(): void
    {
        // Não remove transição manualmente criada — handoff é regra de negócio.
    }
};
