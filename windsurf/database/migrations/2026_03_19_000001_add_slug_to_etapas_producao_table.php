<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('etapas_producao', function (Blueprint $table) {
            $table->string('slug', 50)->nullable()->unique()->after('nome');
        });

        // Atribuir slugs às etapas logísticas existentes
        DB::table('etapas_producao')->where('nome', 'Aguardando Retirada')->update(['slug' => 'aguardando_retirada']);
        DB::table('etapas_producao')->where('nome', 'Coletado')->update(['slug' => 'coletado']);

        // Criar etapas intermediárias do fluxo logístico
        $aguardandoRetirada = DB::table('etapas_producao')->where('slug', 'aguardando_retirada')->first();
        $coletado = DB::table('etapas_producao')->where('slug', 'coletado')->first();

        if ($aguardandoRetirada && $coletado) {
            $now = now();

            // Reordenar: Aguardando Retirada=8, Aguardando Motorista=9, Em Trânsito=10, Coletado=11
            DB::table('etapas_producao')->where('id', $coletado->id)->update(['ordem' => 11]);

            $aguardandoMotoristaId = DB::table('etapas_producao')->insertGetId([
                'nome' => 'Aguardando Motorista',
                'slug' => 'aguardando_motorista',
                'icone' => '🚛',
                'cor' => 'yellow',
                'ordem' => 9,
                'ativo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $emTransitoId = DB::table('etapas_producao')->insertGetId([
                'nome' => 'Em Trânsito',
                'slug' => 'em_transito',
                'icone' => '🚚',
                'cor' => 'orange',
                'ordem' => 10,
                'ativo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Remover transição direta Aguardando Retirada → Coletado
            DB::table('etapas_transicoes')
                ->where('etapa_origem_id', $aguardandoRetirada->id)
                ->where('etapa_destino_id', $coletado->id)
                ->delete();

            // Criar novas transições: Aguardando Retirada → Aguardando Motorista → Em Trânsito → Coletado
            $maxOrdem = DB::table('etapas_transicoes')->max('ordem') ?? 0;

            DB::table('etapas_transicoes')->insert([
                [
                    'etapa_origem_id' => $aguardandoRetirada->id,
                    'etapa_destino_id' => $aguardandoMotoristaId,
                    'label_botao' => null,
                    'cor_botao' => 'yellow',
                    'ativo' => true,
                    'ordem' => $maxOrdem + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'etapa_origem_id' => $aguardandoMotoristaId,
                    'etapa_destino_id' => $emTransitoId,
                    'label_botao' => null,
                    'cor_botao' => 'orange',
                    'ativo' => true,
                    'ordem' => $maxOrdem + 2,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'etapa_origem_id' => $emTransitoId,
                    'etapa_destino_id' => $coletado->id,
                    'label_botao' => null,
                    'cor_botao' => 'green',
                    'ativo' => true,
                    'ordem' => $maxOrdem + 3,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar transição direta Aguardando Retirada → Coletado
        $aguardandoRetirada = DB::table('etapas_producao')->where('slug', 'aguardando_retirada')->first();
        $coletado = DB::table('etapas_producao')->where('slug', 'coletado')->first();

        if ($aguardandoRetirada && $coletado) {
            $now = now();
            $maxOrdem = DB::table('etapas_transicoes')->max('ordem') ?? 0;

            DB::table('etapas_transicoes')->insert([
                'etapa_origem_id' => $aguardandoRetirada->id,
                'etapa_destino_id' => $coletado->id,
                'label_botao' => null,
                'cor_botao' => 'blue',
                'ativo' => true,
                'ordem' => $maxOrdem + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('etapas_producao')->where('id', $coletado->id)->update(['ordem' => 9]);
        }

        // Remover etapas intermediárias e suas transições
        $intermediarias = DB::table('etapas_producao')->whereIn('slug', ['aguardando_motorista', 'em_transito'])->pluck('id');
        DB::table('etapas_transicoes')->whereIn('etapa_origem_id', $intermediarias)->orWhereIn('etapa_destino_id', $intermediarias)->delete();
        DB::table('etapas_producao')->whereIn('id', $intermediarias)->delete();

        Schema::table('etapas_producao', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
