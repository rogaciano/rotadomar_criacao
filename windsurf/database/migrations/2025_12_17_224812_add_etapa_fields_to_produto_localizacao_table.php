<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $etapaAtualForeignExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'produto_localizacao')
            ->where('CONSTRAINT_NAME', 'produto_localizacao_etapa_atual_id_foreign')
            ->exists();

        $etapaAnteriorForeignExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'produto_localizacao')
            ->where('CONSTRAINT_NAME', 'produto_localizacao_etapa_anterior_id_foreign')
            ->exists();

        $etapaAtualIndexExists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'produto_localizacao')
            ->where('INDEX_NAME', 'produto_localizacao_etapa_atual_id_index')
            ->exists();

        Schema::table('produto_localizacao', function (Blueprint $table) {
            if (!Schema::hasColumn('produto_localizacao', 'etapa_atual_id')) {
                $table->unsignedBigInteger('etapa_atual_id')->nullable()->after('concluido');
            }

            if (!Schema::hasColumn('produto_localizacao', 'etapa_anterior_id')) {
                $table->unsignedBigInteger('etapa_anterior_id')->nullable()->after('etapa_atual_id');
            }
        });

        Schema::table('produto_localizacao', function (Blueprint $table) use ($etapaAtualForeignExists, $etapaAnteriorForeignExists, $etapaAtualIndexExists) {
            if (Schema::hasColumn('produto_localizacao', 'etapa_atual_id') && !$etapaAtualForeignExists) {
                $table->foreign('etapa_atual_id')->references('id')->on('etapas_producao')->onDelete('set null');
            }

            if (Schema::hasColumn('produto_localizacao', 'etapa_anterior_id') && !$etapaAnteriorForeignExists) {
                $table->foreign('etapa_anterior_id')->references('id')->on('etapas_producao')->onDelete('set null');
            }

            if (Schema::hasColumn('produto_localizacao', 'etapa_atual_id') && !$etapaAtualIndexExists) {
                $table->index('etapa_atual_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $etapaAtualForeignExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'produto_localizacao')
            ->where('CONSTRAINT_NAME', 'produto_localizacao_etapa_atual_id_foreign')
            ->exists();

        $etapaAnteriorForeignExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'produto_localizacao')
            ->where('CONSTRAINT_NAME', 'produto_localizacao_etapa_anterior_id_foreign')
            ->exists();

        Schema::table('produto_localizacao', function (Blueprint $table) use ($etapaAtualForeignExists, $etapaAnteriorForeignExists) {
            if (Schema::hasColumn('produto_localizacao', 'etapa_atual_id') && $etapaAtualForeignExists) {
                $table->dropForeign(['etapa_atual_id']);
            }

            if (Schema::hasColumn('produto_localizacao', 'etapa_anterior_id') && $etapaAnteriorForeignExists) {
                $table->dropForeign(['etapa_anterior_id']);
            }

            $columns = [];

            if (Schema::hasColumn('produto_localizacao', 'etapa_atual_id')) {
                $columns[] = 'etapa_atual_id';
            }

            if (Schema::hasColumn('produto_localizacao', 'etapa_anterior_id')) {
                $columns[] = 'etapa_anterior_id';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
