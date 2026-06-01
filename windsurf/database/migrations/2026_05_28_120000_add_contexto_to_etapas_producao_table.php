<?php

use App\Models\EtapaProducao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * contexto: onde a etapa é usada (facção/planejamento vs logística).
     * inicia_logistica: etapa que encerra produção na facção e abre o fluxo logístico.
     */
    public function up(): void
    {
        Schema::table('etapas_producao', function (Blueprint $table) {
            if (!Schema::hasColumn('etapas_producao', 'contexto')) {
                $table->string('contexto', 20)->default('localizacao')->after('slug');
                $table->index('contexto');
            }

            if (!Schema::hasColumn('etapas_producao', 'inicia_logistica')) {
                $table->boolean('inicia_logistica')->default(false)->after('contexto');
            }
        });

        $slugsLogistica = [
            EtapaProducao::SLUG_AGUARDANDO_RETIRADA,
            EtapaProducao::SLUG_AGUARDANDO_MOTORISTA,
            EtapaProducao::SLUG_EM_TRANSITO,
            EtapaProducao::SLUG_COLETADO,
        ];

        DB::table('etapas_producao')
            ->whereIn('slug', $slugsLogistica)
            ->update(['contexto' => EtapaProducao::CONTEXTO_LOGISTICA]);

        DB::table('etapas_producao')
            ->where(function ($q) use ($slugsLogistica) {
                $q->whereNull('slug')->orWhereNotIn('slug', $slugsLogistica);
            })
            ->update(['contexto' => EtapaProducao::CONTEXTO_LOCALIZACAO]);

        DB::table('etapas_producao')->update(['inicia_logistica' => false]);

        DB::table('etapas_producao')
            ->where('slug', EtapaProducao::SLUG_AGUARDANDO_RETIRADA)
            ->update(['inicia_logistica' => true]);
    }

    public function down(): void
    {
        Schema::table('etapas_producao', function (Blueprint $table) {
            if (Schema::hasColumn('etapas_producao', 'inicia_logistica')) {
                $table->dropColumn('inicia_logistica');
            }
            if (Schema::hasColumn('etapas_producao', 'contexto')) {
                $table->dropIndex(['contexto']);
                $table->dropColumn('contexto');
            }
        });
    }
};
