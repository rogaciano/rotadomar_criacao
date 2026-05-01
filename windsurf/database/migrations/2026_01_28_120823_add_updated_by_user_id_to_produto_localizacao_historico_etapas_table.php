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
        Schema::table('produto_localizacao_historico_etapas', function (Blueprint $table) {
            if (!Schema::hasColumn('produto_localizacao_historico_etapas', 'updated_by_user_id')) {
                $table->bigInteger('updated_by_user_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_localizacao_historico_etapas', function (Blueprint $table) {
            if (Schema::hasColumn('produto_localizacao_historico_etapas', 'updated_by_user_id')) {
                $table->dropColumn('updated_by_user_id');
            }
        });
    }
};
