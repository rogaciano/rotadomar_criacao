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
        Schema::table('produto_cor', function (Blueprint $table) {
            if (!Schema::hasColumn('produto_cor', 'cor_rgb')) {
                $table->string('cor_rgb', 7)->nullable()->after('codigo_cor');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_cor', function (Blueprint $table) {
            if (Schema::hasColumn('produto_cor', 'cor_rgb')) {
                $table->dropColumn('cor_rgb');
            }
        });
    }
};
