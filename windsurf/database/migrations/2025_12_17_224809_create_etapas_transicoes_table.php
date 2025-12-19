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
        Schema::create('etapas_transicoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etapa_origem_id')->constrained('etapas_producao')->onDelete('cascade');
            $table->foreignId('etapa_destino_id')->constrained('etapas_producao')->onDelete('cascade');
            $table->string('label_botao', 50)->nullable(); // Texto do botão, se null usa nome da etapa destino
            $table->string('cor_botao', 20)->default('blue'); // Cor do botão
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0); // Ordem de exibição dos botões
            $table->timestamps();
            
            $table->index(['etapa_origem_id', 'ativo', 'ordem']);
            $table->unique(['etapa_origem_id', 'etapa_destino_id'], 'etapas_transicoes_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas_transicoes');
    }
};
