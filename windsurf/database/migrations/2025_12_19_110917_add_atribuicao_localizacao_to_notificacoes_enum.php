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
        // Adicionar o novo tipo 'atribuicao_localizacao' ao enum
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN tipo ENUM('nova_movimentacao', 'movimentacao_concluida', 'mudanca_etapa', 'atribuicao_localizacao') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover o tipo 'atribuicao_localizacao' do enum
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN tipo ENUM('nova_movimentacao', 'movimentacao_concluida', 'mudanca_etapa') NOT NULL");
    }
};
