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
        if (!Schema::hasTable('produto_combinacao')) {
            Schema::create('produto_combinacao', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
                $table->string('descricao');
                $table->integer('quantidade_pretendida')->default(0);
                $table->text('observacoes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('produto_combinacao')) {
            Schema::dropIfExists('produto_combinacao');
        }
    }
};
