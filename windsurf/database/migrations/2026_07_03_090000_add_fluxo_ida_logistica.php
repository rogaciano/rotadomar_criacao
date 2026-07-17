<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fluxo de IDA da logística (fábrica → facção).
     *
     * - coletas_logisticas.tipo: direção da coleta ('volta' = facção → fábrica,
     *   'ida' = fábrica → facção). Default 'volta' preserva o comportamento
     *   de todos os registros existentes.
     * - produto_localizacao.fluxo_logistica: marca como o registro entrou no
     *   fluxo logístico ('ida' quando criado pelo "Liberar para Produção");
     *   usado para propagar a direção para a coleta no agendamento.
     */
    public function up(): void
    {
        if (Schema::hasTable('coletas_logisticas') && !Schema::hasColumn('coletas_logisticas', 'tipo')) {
            Schema::table('coletas_logisticas', function (Blueprint $table) {
                $table->string('tipo', 10)->default('volta')->after('destino_localizacao_id');
                $table->index('tipo');
            });
        }

        if (Schema::hasTable('produto_localizacao') && !Schema::hasColumn('produto_localizacao', 'fluxo_logistica')) {
            Schema::table('produto_localizacao', function (Blueprint $table) {
                $table->string('fluxo_logistica', 10)->nullable()->after('etapa_anterior_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('coletas_logisticas') && Schema::hasColumn('coletas_logisticas', 'tipo')) {
            Schema::table('coletas_logisticas', function (Blueprint $table) {
                $table->dropIndex(['tipo']);
                $table->dropColumn('tipo');
            });
        }

        if (Schema::hasTable('produto_localizacao') && Schema::hasColumn('produto_localizacao', 'fluxo_logistica')) {
            Schema::table('produto_localizacao', function (Blueprint $table) {
                $table->dropColumn('fluxo_logistica');
            });
        }
    }
};
