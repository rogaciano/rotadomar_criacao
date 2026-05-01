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
        Schema::table('status', function (Blueprint $table) {
            if (!Schema::hasColumn('status', 'calc_necessidade')) {
                $table->boolean('calc_necessidade')->default(false)->after('ativo')
                    ->comment('Define se produtos com este status devem ter necessidade de tecido calculada (0=Não, 1=Sim)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status', function (Blueprint $table) {
            if (Schema::hasColumn('status', 'calc_necessidade')) {
                $table->dropColumn('calc_necessidade');
            }
        });
    }
};
