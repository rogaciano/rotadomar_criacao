<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Remove os campos localizacao_id e data_prevista_faccao da tabela produtos
     * Estes dados agora estão na tabela produto_localizacao
     */
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Verificar se a coluna existe antes de tentar remover
            if (Schema::hasColumn('produtos', 'localizacao_id')) {
                // Tentar remover a foreign key constraint primeiro (se existir)
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'produtos' 
                    AND COLUMN_NAME = 'localizacao_id' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                foreach ($foreignKeys as $fk) {
                    try {
                        DB::statement("ALTER TABLE produtos DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                    } catch (\Exception $e) {
                        // Continuar se não conseguir remover
                    }
                }
                
                // Remover as colunas
                $table->dropColumn(['localizacao_id']);
            }
            
            if (Schema::hasColumn('produtos', 'data_prevista_faccao')) {
                $table->dropColumn(['data_prevista_faccao']);
            }
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Restaura os campos localizacao_id e data_prevista_faccao na tabela produtos
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('localizacao_id')->nullable()->after('status_id');
            $table->date('data_prevista_faccao')->nullable()->after('data_prevista_producao');
            
            // Recriar a foreign key se necessário
            $table->foreign('localizacao_id')
                ->references('id')
                ->on('localizacoes')
                ->onDelete('set null');
        });
    }
};
