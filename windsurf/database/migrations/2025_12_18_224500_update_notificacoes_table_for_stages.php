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
        // Alterar o enum e permitir null no movimentacao_id
        // No MySQL, alterar ENUM via Blueprint as vezes é chato, 
        // mas vamos tentar pelo Schema ou DB direto.
        
        Schema::table('notificacoes', function (Blueprint $table) {
            $table->foreignId('movimentacao_id')->nullable()->change();
        });

        // Adicionar tipo ao enum. No Laravel 10+, enum change é suportado se doctrine/dbal estiver instalado,
        // mas aqui vamos usar SQL puro para garantir.
        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN tipo ENUM('nova_movimentacao', 'movimentacao_concluida', 'mudanca_etapa') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notificacoes', function (Blueprint $table) {
            $table->foreignId('movimentacao_id')->nullable(false)->change();
        });

        DB::statement("ALTER TABLE notificacoes MODIFY COLUMN tipo ENUM('nova_movimentacao', 'movimentacao_concluida') NOT NULL");
    }
};
