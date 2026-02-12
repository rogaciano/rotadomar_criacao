<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Corrige o tipo da coluna produto_localizacao_id e adiciona a foreign key ausente.
     */
    public function up(): void
    {
        // Remover orphaned records antes de adicionar a FK
        DB::statement('DELETE FROM produto_localizacao_historico_etapas WHERE produto_localizacao_id NOT IN (SELECT id FROM produto_localizacao)');

        // Reverter coluna para bigint (signed) caso a migration anterior tenha alterado para unsigned
        Schema::table('produto_localizacao_historico_etapas', function (Blueprint $table) {
            $table->bigInteger('produto_localizacao_id')->change();
        });

        // Adicionar a foreign key (nome curto para evitar limite de 64 chars do MySQL)
        // Ambas as colunas são bigint (signed) - produto_localizacao.id e produto_localizacao_id
        Schema::table('produto_localizacao_historico_etapas', function (Blueprint $table) {
            $table->foreign('produto_localizacao_id', 'hist_etapas_pl_id_foreign')
                ->references('id')
                ->on('produto_localizacao')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_localizacao_historico_etapas', function (Blueprint $table) {
            $table->dropForeign('hist_etapas_pl_id_foreign');
        });
    }
};
