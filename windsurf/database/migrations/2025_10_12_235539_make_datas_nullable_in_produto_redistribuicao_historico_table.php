<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('produto_redistribuicao_historico')) {
            Schema::table('produto_redistribuicao_historico', function (Blueprint $table) {
                $table->date('data_prevista_origem')->nullable()->change();
                $table->date('data_prevista_destino')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('produto_redistribuicao_historico')) {
            Schema::table('produto_redistribuicao_historico', function (Blueprint $table) {
                $table->date('data_prevista_origem')->nullable(false)->change();
                $table->date('data_prevista_destino')->nullable(false)->change();
            });
        }
    }
};
