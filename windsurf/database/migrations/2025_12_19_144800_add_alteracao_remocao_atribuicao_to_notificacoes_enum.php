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
        // Adicionar os novos tipos 'alteracao_atribuicao' e 'remocao_atribuicao' ao enum
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN tipo ENUM('nova_movimentacao', 'movimentacao_concluida', 'mudanca_etapa', 'atribuicao_localizacao', 'alteracao_atribuicao', 'remocao_atribuicao') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voltar para o estado anterior
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN tipo ENUM('nova_movimentacao', 'movimentacao_concluida', 'mudanca_etapa', 'atribuicao_localizacao') NOT NULL");
    }
};
