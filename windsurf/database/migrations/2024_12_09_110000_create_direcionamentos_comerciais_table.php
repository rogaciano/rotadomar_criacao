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
        Schema::create('direcionamentos_comerciais', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 100);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Adicionar campo na tabela produtos
        Schema::table('produtos', function (Blueprint $table) {
            $table->foreignId('direcionamento_comercial_id')->nullable()->after('status_id')->constrained('direcionamentos_comerciais')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign(['direcionamento_comercial_id']);
            $table->dropColumn('direcionamento_comercial_id');
        });

        Schema::dropIfExists('direcionamentos_comerciais');
    }
};
