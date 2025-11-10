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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimentacao_id')->constrained('movimentacoes')->onDelete('cascade');
            $table->foreignId('localizacao_id')->constrained('localizacoes')->onDelete('cascade');
            $table->enum('tipo', ['nova_movimentacao', 'movimentacao_concluida']);
            $table->string('titulo');
            $table->text('mensagem');
            $table->string('link');
            $table->foreignId('visualizada_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('visualizada_em')->nullable();
            $table->timestamps();
            
            // Índices para performance
            $table->index(['localizacao_id', 'created_at']);
            $table->index(['visualizada_por', 'visualizada_em']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
