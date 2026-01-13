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
        // Forçar a coluna movimentacao_id a ser nullable na tabela notificacoes
        // Usamos SQL puro para garantir a compatibilidade e evitar problemas com FKs no MySQL
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN movimentacao_id BIGINT UNSIGNED NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN movimentacao_id BIGINT UNSIGNED NOT NULL");
    }
};
