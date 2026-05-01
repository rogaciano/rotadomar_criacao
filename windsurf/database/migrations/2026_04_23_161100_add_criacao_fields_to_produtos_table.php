<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            if (!Schema::hasColumn('produtos', 'data_entrada_processo')) {
                $table->date('data_entrada_processo')->nullable()->after('data_cadastro');
            }

            if (!Schema::hasColumn('produtos', 'obs_designer')) {
                $table->text('obs_designer')->nullable()->after('descricao');
            }

            if (!Schema::hasColumn('produtos', 'etapa_producao_id')) {
                $table->foreignId('etapa_producao_id')->nullable()->after('direcionamento_comercial_id')->constrained('etapas_producao')->nullOnDelete();
            }

            $table->index(['data_entrada_processo', 'etapa_producao_id'], 'idx_produtos_criacao_fluxo');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            if (Schema::hasColumn('produtos', 'etapa_producao_id')) {
                $table->dropForeign(['etapa_producao_id']);
            }

            $table->dropIndex('idx_produtos_criacao_fluxo');

            $columns = [];
            if (Schema::hasColumn('produtos', 'data_entrada_processo')) {
                $columns[] = 'data_entrada_processo';
            }
            if (Schema::hasColumn('produtos', 'obs_designer')) {
                $columns[] = 'obs_designer';
            }
            if (Schema::hasColumn('produtos', 'etapa_producao_id')) {
                $columns[] = 'etapa_producao_id';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
