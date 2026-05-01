<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            if (!Schema::hasColumn('produtos', 'prioridade')) {
                $table->string('prioridade')->nullable()->after('status_id');
            }

            if (!Schema::hasColumn('produtos', 'links_produto')) {
                $table->json('links_produto')->nullable()->after('prioridade');
            }

            if (!Schema::hasColumn('produtos', 'media_mensal')) {
                $table->unsignedInteger('media_mensal')->nullable()->after('quantidade');
            }

            if (!Schema::hasColumn('produtos', 'variantes_cores')) {
                $table->unsignedInteger('variantes_cores')->nullable()->after('media_mensal');
            }

            if (!Schema::hasColumn('produtos', 'faccao_localizacao_id')) {
                $table->unsignedBigInteger('faccao_localizacao_id')->nullable()->after('variantes_cores');
            }

            if (!Schema::hasColumn('produtos', 'responsavel_criacao_id')) {
                $table->unsignedBigInteger('responsavel_criacao_id')->nullable()->after('faccao_localizacao_id');
            }

            if (!Schema::hasColumn('produtos', 'data_entrega')) {
                $table->date('data_entrega')->nullable()->after('responsavel_criacao_id');
            }

            if (!Schema::hasColumn('produtos', 'mes_criacao')) {
                $table->date('mes_criacao')->nullable()->after('data_entrega');
            }

            if (!Schema::hasColumn('produtos', 'mes_producao')) {
                $table->date('mes_producao')->nullable()->after('mes_criacao');
            }

            if (!Schema::hasColumn('produtos', 'mes_lancamento')) {
                $table->date('mes_lancamento')->nullable()->after('mes_producao');
            }

            if (!Schema::hasColumn('produtos', 'observacoes_criacao')) {
                $table->text('observacoes_criacao')->nullable()->after('obs_designer');
            }

            if (!Schema::hasColumn('produtos', 'observacoes_adicionais')) {
                $table->text('observacoes_adicionais')->nullable()->after('observacoes_criacao');
            }

            if (!Schema::hasColumn('produtos', 'foto_principal_criacao')) {
                $table->string('foto_principal_criacao')->nullable()->after('observacoes_adicionais');
            }

            if (!Schema::hasColumn('produtos', 'foto_principal_desenvolvimento')) {
                $table->string('foto_principal_desenvolvimento')->nullable()->after('foto_principal_criacao');
            }
        });

        if (Schema::hasTable('produto_anexos')) {
            Schema::table('produto_anexos', function (Blueprint $table) {
                if (!Schema::hasColumn('produto_anexos', 'contexto')) {
                    $table->string('contexto')->nullable()->after('tipo_arquivo');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('produto_anexos')) {
            Schema::table('produto_anexos', function (Blueprint $table) {
                if (Schema::hasColumn('produto_anexos', 'contexto')) {
                    $table->dropColumn('contexto');
                }
            });
        }

        Schema::table('produtos', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'prioridade',
                'links_produto',
                'media_mensal',
                'variantes_cores',
                'faccao_localizacao_id',
                'responsavel_criacao_id',
                'data_entrega',
                'mes_criacao',
                'mes_producao',
                'mes_lancamento',
                'observacoes_criacao',
                'observacoes_adicionais',
                'foto_principal_criacao',
                'foto_principal_desenvolvimento',
            ] as $column) {
                if (Schema::hasColumn('produtos', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
