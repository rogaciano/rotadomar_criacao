<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$produtos = App\Models\Produto::query()
    ->whereHas('status', fn ($q) => $q->whereRaw("UPPER(TRIM(descricao)) = 'DESENVOLVIMENTO FINALIZADO'"))
    ->whereDoesntHave('localizacoes', fn ($q) => $q->whereHas('etapaAtual', fn ($e) => $e->where('contexto', 'logistica')))
    ->with(['localizacoes.localizacao'])
    ->take(5)
    ->get(['id', 'referencia']);

foreach ($produtos as $p) {
    $locs = $p->localizacoes->map(fn ($pl) => $pl->localizacao?->nome_localizacao)->filter()->join(' | ');
    echo "{$p->referencia} (id {$p->id}): {$locs}\n";
}
