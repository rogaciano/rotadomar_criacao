<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $hasDeletedAt = Schema::hasColumn('permissions', 'deleted_at');
        $existing = DB::table('permissions')
            ->where('name', 'produto_observacoes')
            ->first();

        if ($existing) {
            $updates = [
                'display_name' => 'Observações de Produtos',
                'description' => 'Gerenciamento de observações de produtos',
                'updated_at' => now(),
            ];

            if ($hasDeletedAt) {
                $updates['deleted_at'] = null;
            }

            DB::table('permissions')
                ->where('id', $existing->id)
                ->update($updates);

            return;
        }

        DB::table('permissions')->insert([
            'name' => 'produto_observacoes',
            'display_name' => 'Observações de Produtos',
            'description' => 'Gerenciamento de observações de produtos',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intencionalmente vazio para evitar remover uma permissao
        // que ja possa existir em ambientes antigos.
    }
};
