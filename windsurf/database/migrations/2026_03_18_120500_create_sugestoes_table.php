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
        Schema::create('sugestoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('localizacao_id')->nullable()->constrained('localizacoes')->nullOnDelete();
            $table->string('assunto', 255);
            $table->text('texto');
            $table->enum('status', ['nao_lida', 'lida', 'em_analise', 'aceito', 'negado'])->default('nao_lida');
            $table->foreignId('lido_por_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('lido_em')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('localizacao_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugestoes');
    }
};
