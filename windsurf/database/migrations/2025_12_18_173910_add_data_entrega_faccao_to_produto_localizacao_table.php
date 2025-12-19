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
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $table->date('data_entrega_faccao')->nullable()->after('data_retorno_faccao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $table->dropColumn('data_entrega_faccao');
        });
    }
};
