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
            if (Schema::hasColumn('produto_componentes', 'descricao')) {
                $table->dropColumn('descricao');
            }

            if (Schema::hasColumn('produto_componentes', 'quantidade_pretendida')) {
                $table->dropColumn('quantidade_pretendida');
            }

            if (!Schema::hasColumn('produto_componentes', 'produto_combinacao_id')) {
                $table->foreignId('produto_combinacao_id')->after('produto_id')->nullable();
                $table->index('produto_combinacao_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_componentes', function (Blueprint $table) {
            if (Schema::hasColumn('produto_componentes', 'produto_combinacao_id')) {
                $table->dropIndex(['produto_combinacao_id']);
                $table->dropColumn('produto_combinacao_id');
            }

            if (!Schema::hasColumn('produto_componentes', 'descricao')) {
                $table->string('descricao', 255)->nullable()->after('produto_id');
            }

            if (!Schema::hasColumn('produto_componentes', 'quantidade_pretendida')) {
                $table->decimal('quantidade_pretendida', 10, 2)->nullable()->default(0)->after('quantidade');
            }
        });
    }
};
