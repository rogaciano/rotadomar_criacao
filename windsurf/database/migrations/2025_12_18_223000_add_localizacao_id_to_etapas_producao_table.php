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
        Schema::table('etapas_producao', function (Blueprint $table) {
            $table->foreignId('localizacao_id')->nullable()->after('ativo')->constrained('localizacoes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etapas_producao', function (Blueprint $table) {
            $table->dropForeign(['localizacao_id']);
            $table->dropColumn('localizacao_id');
        });
    }
};
