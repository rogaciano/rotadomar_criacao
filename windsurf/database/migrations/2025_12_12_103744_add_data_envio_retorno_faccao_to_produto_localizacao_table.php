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
            if (!Schema::hasColumn('produto_localizacao', 'data_envio_faccao')) {
                $table->date('data_envio_faccao')->nullable()->after('data_prevista_faccao');
            }

            if (!Schema::hasColumn('produto_localizacao', 'data_retorno_faccao')) {
                $table->date('data_retorno_faccao')->nullable()->after('data_envio_faccao');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('produto_localizacao', 'data_envio_faccao')) {
                $columns[] = 'data_envio_faccao';
            }

            if (Schema::hasColumn('produto_localizacao', 'data_retorno_faccao')) {
                $columns[] = 'data_retorno_faccao';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
