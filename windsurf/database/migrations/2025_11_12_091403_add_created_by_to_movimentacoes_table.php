<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $foreignKeyExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'movimentacoes')
            ->where('CONSTRAINT_NAME', 'movimentacoes_created_by_foreign')
            ->exists();

        Schema::table('movimentacoes', function (Blueprint $table) {
            if (!Schema::hasColumn('movimentacoes', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('concluido');
            }
        });

        if (Schema::hasColumn('movimentacoes', 'created_by') && !$foreignKeyExists) {
            Schema::table('movimentacoes', function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $foreignKeyExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'movimentacoes')
            ->where('CONSTRAINT_NAME', 'movimentacoes_created_by_foreign')
            ->exists();

        Schema::table('movimentacoes', function (Blueprint $table) use ($foreignKeyExists) {
            if (Schema::hasColumn('movimentacoes', 'created_by') && $foreignKeyExists) {
                $table->dropForeign(['created_by']);
            }

            if (Schema::hasColumn('movimentacoes', 'created_by')) {
                $table->dropColumn('created_by');
            }
        });
    }
};
