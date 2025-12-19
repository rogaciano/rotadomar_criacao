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
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $table->foreignId('etapa_atual_id')->nullable()->after('concluido')
                ->constrained('etapas_producao')->onDelete('set null');
            $table->foreignId('etapa_anterior_id')->nullable()->after('etapa_atual_id')
                ->constrained('etapas_producao')->onDelete('set null');
            
            $table->index('etapa_atual_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $table->dropForeign(['etapa_atual_id']);
            $table->dropForeign(['etapa_anterior_id']);
            $table->dropColumn(['etapa_atual_id', 'etapa_anterior_id']);
        });
    }
};
