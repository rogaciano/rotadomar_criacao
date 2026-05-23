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
        Schema::table('produto_componentes', function (Blueprint $table) {
            if (!Schema::hasColumn('produto_componentes', 'descricao')) {
                $table->string('descricao', 255)->nullable()->after('produto_id');
            }

            if (!Schema::hasColumn('produto_componentes', 'quantidade_pretendida')) {
                $table->decimal('quantidade_pretendida', 10, 2)->nullable()->default(0)->after('quantidade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_componentes', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('produto_componentes', 'descricao')) {
                $columns[] = 'descricao';
            }

            if (Schema::hasColumn('produto_componentes', 'quantidade_pretendida')) {
                $columns[] = 'quantidade_pretendida';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
