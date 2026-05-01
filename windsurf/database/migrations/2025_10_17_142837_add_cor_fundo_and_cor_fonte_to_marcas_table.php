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
        Schema::table('marcas', function (Blueprint $table) {
            if (!Schema::hasColumn('marcas', 'cor_fundo')) {
                $table->string('cor_fundo', 10)->nullable()->after('logo_path')
                    ->comment('Cor de fundo em hexadecimal (ex: #FF5733)');
            }

            if (!Schema::hasColumn('marcas', 'cor_fonte')) {
                $table->string('cor_fonte', 10)->nullable()->after('cor_fundo')
                    ->comment('Cor da fonte em hexadecimal (ex: #FFFFFF)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marcas', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('marcas', 'cor_fundo')) {
                $columns[] = 'cor_fundo';
            }

            if (Schema::hasColumn('marcas', 'cor_fonte')) {
                $columns[] = 'cor_fonte';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
