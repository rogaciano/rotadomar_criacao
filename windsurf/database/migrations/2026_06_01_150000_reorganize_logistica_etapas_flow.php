<?php

use App\Models\EtapaProducao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('etapas_producao') || !Schema::hasTable('etapas_transicoes')) {
            return;
        }

        DB::transaction(function () {
            $etapasConfig = [
                EtapaProducao::SLUG_AGENDAMENTO => [
                    'legacy_slug' => EtapaProducao::SLUG_AGUARDANDO_RETIRADA,
                    'nome' => 'Agendamento',
                    'icone' => '🗓️',
                    'cor' => 'gray',
                    'ordem' => 1,
                    'inicia_logistica' => true,
                ],
                EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA => [
                    'legacy_slug' => EtapaProducao::SLUG_AGUARDANDO_MOTORISTA,
                    'nome' => 'Saída da Fábrica / Solicitar Retirada',
                    'icone' => '📤',
                    'cor' => 'yellow',
                    'ordem' => 2,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_RETIRADA_CONFIRMADA_FACCAO => [
                    'legacy_slug' => null,
                    'nome' => 'Retirada Confirmada pela Facção',
                    'icone' => '📦',
                    'cor' => 'orange',
                    'ordem' => 3,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_EM_TRANSITO => [
                    'legacy_slug' => EtapaProducao::SLUG_EM_TRANSITO,
                    'nome' => 'Em Trânsito',
                    'icone' => '🚚',
                    'cor' => 'orange',
                    'ordem' => 4,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA => [
                    'legacy_slug' => null,
                    'nome' => 'Entrega Confirmada na Fábrica',
                    'icone' => '🏭',
                    'cor' => 'indigo',
                    'ordem' => 5,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_CHECK_IN => [
                    'legacy_slug' => null,
                    'nome' => 'Check-in',
                    'icone' => '🧾',
                    'cor' => 'blue',
                    'ordem' => 6,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_CHEGADA_PRODUTO_FABRICA => [
                    'legacy_slug' => EtapaProducao::SLUG_COLETADO,
                    'nome' => 'Chegada do Produto na Fábrica',
                    'icone' => '✅',
                    'cor' => 'green',
                    'ordem' => 7,
                    'inicia_logistica' => false,
                ],
            ];

            DB::table('etapas_producao')
                ->where('contexto', EtapaProducao::CONTEXTO_LOGISTICA)
                ->update(['inicia_logistica' => false]);

            $etapaIds = [];

            foreach ($etapasConfig as $slug => $config) {
                $registro = DB::table('etapas_producao')->where('slug', $slug)->first();

                if (!$registro && $config['legacy_slug']) {
                    $registro = DB::table('etapas_producao')->where('slug', $config['legacy_slug'])->first();
                }

                $payload = [
                    'nome' => $config['nome'],
                    'slug' => $slug,
                    'contexto' => EtapaProducao::CONTEXTO_LOGISTICA,
                    'inicia_logistica' => $config['inicia_logistica'],
                    'icone' => $config['icone'],
                    'cor' => $config['cor'],
                    'ordem' => $config['ordem'],
                    'ativo' => true,
                    'updated_at' => now(),
                ];

                if ($registro) {
                    DB::table('etapas_producao')->where('id', $registro->id)->update($payload);
                    $etapaIds[$slug] = $registro->id;
                    continue;
                }

                $etapaIds[$slug] = DB::table('etapas_producao')->insertGetId(array_merge($payload, [
                    'created_at' => now(),
                ]));
            }

            $etapasLogisticasIds = array_values($etapaIds);

            DB::table('etapas_transicoes')
                ->whereIn('etapa_origem_id', $etapasLogisticasIds)
                ->whereIn('etapa_destino_id', $etapasLogisticasIds)
                ->delete();

            $transicoes = [
                [EtapaProducao::SLUG_AGENDAMENTO, EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA, 'Solicitar retirada', 'yellow'],
                [EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA, EtapaProducao::SLUG_RETIRADA_CONFIRMADA_FACCAO, 'Confirmar retirada', 'orange'],
                [EtapaProducao::SLUG_RETIRADA_CONFIRMADA_FACCAO, EtapaProducao::SLUG_EM_TRANSITO, 'Em trânsito', 'orange'],
                [EtapaProducao::SLUG_EM_TRANSITO, EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA, 'Confirmar entrega', 'indigo'],
                [EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA, EtapaProducao::SLUG_CHECK_IN, 'Registrar check-in', 'blue'],
                [EtapaProducao::SLUG_CHECK_IN, EtapaProducao::SLUG_CHEGADA_PRODUTO_FABRICA, 'Confirmar chegada', 'green'],
            ];

            foreach ($transicoes as $ordem => [$origemSlug, $destinoSlug, $label, $cor]) {
                DB::table('etapas_transicoes')->insert([
                    'etapa_origem_id' => $etapaIds[$origemSlug],
                    'etapa_destino_id' => $etapaIds[$destinoSlug],
                    'label_botao' => $label,
                    'cor_botao' => $cor,
                    'ativo' => true,
                    'ordem' => $ordem,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('etapas_producao')) {
            return;
        }

        DB::transaction(function () {
            $mapaReverso = [
                EtapaProducao::SLUG_AGENDAMENTO => [
                    'slug' => EtapaProducao::SLUG_AGUARDANDO_RETIRADA,
                    'nome' => 'Aguardando Retirada',
                    'icone' => '📍',
                    'cor' => 'gray',
                    'ordem' => 1,
                    'inicia_logistica' => true,
                ],
                EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA => [
                    'slug' => EtapaProducao::SLUG_AGUARDANDO_MOTORISTA,
                    'nome' => 'Aguardando Motorista',
                    'icone' => '🚛',
                    'cor' => 'yellow',
                    'ordem' => 2,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_EM_TRANSITO => [
                    'slug' => EtapaProducao::SLUG_EM_TRANSITO,
                    'nome' => 'Em Trânsito',
                    'icone' => '🚚',
                    'cor' => 'orange',
                    'ordem' => 3,
                    'inicia_logistica' => false,
                ],
                EtapaProducao::SLUG_CHEGADA_PRODUTO_FABRICA => [
                    'slug' => EtapaProducao::SLUG_COLETADO,
                    'nome' => 'Coletado',
                    'icone' => '✅',
                    'cor' => 'green',
                    'ordem' => 4,
                    'inicia_logistica' => false,
                ],
            ];

            foreach ($mapaReverso as $slugAtual => $config) {
                DB::table('etapas_producao')
                    ->where('slug', $slugAtual)
                    ->update([
                        'slug' => $config['slug'],
                        'nome' => $config['nome'],
                        'icone' => $config['icone'],
                        'cor' => $config['cor'],
                        'ordem' => $config['ordem'],
                        'inicia_logistica' => $config['inicia_logistica'],
                        'updated_at' => now(),
                    ]);
            }

            DB::table('etapas_producao')
                ->whereIn('slug', [
                    EtapaProducao::SLUG_RETIRADA_CONFIRMADA_FACCAO,
                    EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA,
                    EtapaProducao::SLUG_CHECK_IN,
                ])
                ->delete();
        });
    }
};
