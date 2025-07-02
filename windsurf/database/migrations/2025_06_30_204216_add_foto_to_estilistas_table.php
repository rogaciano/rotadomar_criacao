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
        Schema::table('estilistas', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('suporte_marca')->comment('Caminho para a foto do estilista');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estilistas', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
