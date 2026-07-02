<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * O código (ColetaLogistica::STATUS_ENTREGUE) grava 'entregue' em
     * confirmarEntregaFabrica, mas o enum original da tabela não tinha
     * esse valor — sem esta migration a confirmação de entrega falha.
     */
    public function up(): void
    {
        if (!Schema::hasTable('coletas_logisticas')) {
            return;
        }

        DB::statement(
            "ALTER TABLE coletas_logisticas
             MODIFY COLUMN status ENUM('agendado', 'em_transito', 'entregue', 'finalizado', 'cancelado')
             NOT NULL DEFAULT 'agendado'"
        );
    }

    public function down(): void
    {
        if (!Schema::hasTable('coletas_logisticas')) {
            return;
        }

        // Coletas 'entregue' viram 'em_transito' para não violar o enum antigo
        DB::table('coletas_logisticas')
            ->where('status', 'entregue')
            ->update(['status' => 'em_transito']);

        DB::statement(
            "ALTER TABLE coletas_logisticas
             MODIFY COLUMN status ENUM('agendado', 'em_transito', 'finalizado', 'cancelado')
             NOT NULL DEFAULT 'agendado'"
        );
    }
};
