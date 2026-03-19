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
        Schema::create('coletas_logisticas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('produto_localizacao_id');
            $table->foreign('produto_localizacao_id')->references('id')->on('produto_localizacao')->cascadeOnDelete();
            $table->foreignId('motorista_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('veiculo_id')->constrained('veiculos')->cascadeOnDelete();
            $table->foreignId('destino_localizacao_id')->constrained('localizacoes')->cascadeOnDelete();
            $table->dateTime('inicio_previsto_em');
            $table->dateTime('retorno_previsto_em');
            $table->dateTime('chegada_origem_em')->nullable();
            $table->dateTime('recebido_destino_em')->nullable();
            $table->enum('status', ['agendado', 'em_transito', 'finalizado', 'cancelado'])->default('agendado');
            $table->text('observacao_motorista')->nullable();
            $table->text('observacao_origem')->nullable();
            $table->text('observacao_destino')->nullable();
            $table->timestamps();

            $table->index(['motorista_user_id', 'inicio_previsto_em', 'retorno_previsto_em'], 'coletas_motorista_agenda_idx');
            $table->index(['veiculo_id', 'inicio_previsto_em', 'retorno_previsto_em'], 'coletas_veiculo_agenda_idx');
            $table->index(['produto_localizacao_id', 'status'], 'coletas_prodloc_status_idx');
            $table->index('destino_localizacao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coletas_logisticas');
    }
};
