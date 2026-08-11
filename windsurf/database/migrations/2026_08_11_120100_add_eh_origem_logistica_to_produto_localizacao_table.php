<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $table->boolean('eh_origem_logistica')->default(false)->after('fluxo_logistica');
            $table->index(['produto_id', 'eh_origem_logistica'], 'produto_localizacao_origem_idx');
        });

        DB::table('produto_localizacao')
            ->where('fluxo_logistica', 'ida')
            ->update(['eh_origem_logistica' => true]);
    }

    public function down(): void
    {
        Schema::table('produto_localizacao', function (Blueprint $table) {
            $table->dropIndex('produto_localizacao_origem_idx');
            $table->dropColumn('eh_origem_logistica');
        });
    }
};
