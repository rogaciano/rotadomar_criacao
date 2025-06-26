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
        Schema::table('tecidos', function (Blueprint $table) {
            # campo data ultima_consulta_estoque
            $table->date('ultima_consulta_estoque')->nullable();
            # campo quantidade_estoque
            $table->decimal('quantidade_estoque', 10, 2)->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tecidos', function (Blueprint $table) {
            $table->dropColumn('quantidade_estoque');
            $table->dropColumn('ultima_consulta_estoque');
            //
        });
    }
};
