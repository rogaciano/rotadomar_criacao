<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permissão para liberar produtos com desenvolvimento finalizado
     * para produção (início do fluxo logístico de ida).
     */
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $hasDeletedAt = Schema::hasColumn('permissions', 'deleted_at');
        $existing = DB::table('permissions')
            ->where('name', 'liberar_producao')
            ->first();

        if ($existing) {
            $updates = [
                'display_name' => 'Liberar para Produção',
                'description' => 'Liberar produtos com desenvolvimento finalizado para o fluxo logístico de ida (fábrica → facção)',
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
            'name' => 'liberar_producao',
            'display_name' => 'Liberar para Produção',
            'description' => 'Liberar produtos com desenvolvimento finalizado para o fluxo logístico de ida (fábrica → facção)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Intencionalmente vazio para evitar remover uma permissão
        // que já possa existir em ambientes antigos.
    }
};
