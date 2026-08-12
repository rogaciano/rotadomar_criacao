<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('produto_localizacao', 'destino_planejado_produto_localizacao_id')) {
            Schema::table('produto_localizacao', function (Blueprint $table) {
                $table->unsignedBigInteger('destino_planejado_produto_localizacao_id')
                    ->nullable()
                    ->after('eh_origem_logistica');
                $table->index(
                    ['produto_id', 'destino_planejado_produto_localizacao_id'],
                    'produto_localizacao_destino_planejado_idx'
                );
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('produto_localizacao', 'destino_planejado_produto_localizacao_id')) {
            Schema::table('produto_localizacao', function (Blueprint $table) {
                $table->dropIndex('produto_localizacao_destino_planejado_idx');
                $table->dropColumn('destino_planejado_produto_localizacao_id');
            });
        }
    }
};
