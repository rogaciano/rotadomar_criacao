<?php

namespace App\Console\Commands;

use App\Models\EtapaProducao;
use App\Models\EtapaTransicao;
use App\Models\ProdutoLocalizacao;
use Illuminate\Console\Command;

class AuditarFluxoLogistica extends Command
{
    protected $signature = 'etapas:auditar-logistica';

    protected $description = 'Audita etapas logísticas, handoff e transições do fluxo';

    public function handle(): int
    {
        $this->info('=== Etapas logística ===');

        $logistica = EtapaProducao::paraLogistica()->orderBy('ordem')->get();
        if ($logistica->isEmpty()) {
            $this->error('Nenhuma etapa logística encontrada. Rode: php artisan migrate');
            return self::FAILURE;
        }

        foreach ($logistica as $e) {
            $flag = $e->inicia_logistica ? ' [INÍCIO LOG]' : '';
            $this->line(sprintf(
                '  %d. %s (slug=%s, ativo=%s)%s',
                $e->ordem,
                $e->nome,
                $e->slug ?? '-',
                $e->ativo ? 'sim' : 'não',
                $flag
            ));
        }

        $inicio = EtapaProducao::etapaInicioLogistica();
        if (!$inicio) {
            $this->error('Nenhuma etapa com inicia_logistica=true.');
            return self::FAILURE;
        }
        $this->newLine();
        $this->info("Handoff (início logística): {$inicio->nome} ({$inicio->slug})");

        $acabamento = EtapaProducao::where('nome', 'Acabamento')->paraLocalizacao()->first();
        if ($acabamento) {
            $handoff = EtapaTransicao::where('etapa_origem_id', $acabamento->id)
                ->where('etapa_destino_id', $inicio->id)
                ->exists();
            $this->info($handoff
                ? 'OK: Transição Acabamento → início logística existe.'
                : 'ERRO: Falta transição Acabamento → início logística.');
        }

        $this->newLine();
        $this->info('Transições internas logística:');
        foreach ($logistica as $e) {
            $destinos = $e->transicoesOrigem()->with('etapaDestino')->orderBy('ordem')->get();
            foreach ($destinos as $t) {
                $d = $t->etapaDestino;
                if ($d) {
                    $this->line("  {$e->slug} → {$d->slug}");
                }
            }
        }

        $qtd = ProdutoLocalizacao::where('etapa_atual_id', $inicio->id)->count();
        $this->newLine();
        $this->info("Produtos aguardando em {$inicio->nome}: {$qtd}");

        $legado = EtapaProducao::whereIn('slug', [
            EtapaProducao::SLUG_AGUARDANDO_RETIRADA,
            EtapaProducao::SLUG_AGUARDANDO_MOTORISTA,
            EtapaProducao::SLUG_COLETADO,
        ])->where('ativo', true)->count();

        if ($legado > 0) {
            $this->warn("Atenção: {$legado} etapa(s) legada(s) ainda ativa(s). Considere rodar a migration reorganize_logistica.");
        }

        return self::SUCCESS;
    }
}
