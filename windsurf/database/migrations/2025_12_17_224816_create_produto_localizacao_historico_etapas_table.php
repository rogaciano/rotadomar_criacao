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
        if (!Schema::hasTable('produto_localizacao_historico_etapas')) {
            Schema::create('produto_localizacao_historico_etapas', function (Blueprint $table) {
                $table->id();
                // Sem FK para produto_localizacao devido a incompatibilidade de tipos (bigint vs bigint unsigned)
                $table->bigInteger('produto_localizacao_id');
                $table->unsignedBigInteger('etapa_anterior_id')->nullable();
                $table->unsignedBigInteger('etapa_nova_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->string('acao', 20); // 'avancar', 'voltar', 'definir_inicial'
                $table->text('observacao')->nullable();
                $table->timestamps();

                // Índices
                $table->index(['produto_localizacao_id', 'created_at'], 'hist_etapas_pl_created_idx');
                $table->index('user_id');
                $table->index('etapa_anterior_id');
                $table->index('etapa_nova_id');

                // Foreign keys para tabelas compatíveis
                $table->foreign('etapa_anterior_id')->references('id')->on('etapas_producao')->onDelete('set null');
                $table->foreign('etapa_nova_id')->references('id')->on('etapas_producao')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('produto_localizacao_historico_etapas')) {
            Schema::dropIfExists('produto_localizacao_historico_etapas');
        }
    }
};
