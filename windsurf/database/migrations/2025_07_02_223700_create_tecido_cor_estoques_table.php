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
        Schema::create('tecido_cor_estoques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tecido_id')->constrained()->onDelete('cascade');
            $table->string('cor', 100)->comment('Nome/código da cor do tecido');
            $table->string('codigo_cor', 50)->nullable()->comment('Código de referência da cor');
            $table->decimal('quantidade', 10, 2)->default(0)->comment('Quantidade em estoque desta cor');
            $table->date('data_atualizacao')->comment('Data da última atualização do estoque');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            // Índice composto para garantir que não haja duplicidade de cor para o mesmo tecido
            $table->unique(['tecido_id', 'cor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecido_cor_estoques');
    }
};
