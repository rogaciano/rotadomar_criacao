<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('localizacoes', function (Blueprint $table) {
            $table->boolean('origem_logistica_padrao')->default(false)->after('faz_movimentacao');
        });
    }

    public function down(): void
    {
        Schema::table('localizacoes', function (Blueprint $table) {
            $table->dropColumn('origem_logistica_padrao');
        });
    }
};
