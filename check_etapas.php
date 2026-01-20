<?php
require __DIR__ . '/windsurf/vendor/autoload.php';
$app = require_once __DIR__ . '/windsurf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EtapaProducao;
use App\Models\EtapaTransicao;

echo "--- ETAPAS ---\n";
$etapas = EtapaProducao::all();
foreach ($etapas as $e) {
    echo "ID: {$e->id}, Nome: [{$e->nome}], Cor: [{$e->cor}], Ativo: " . ($e->ativo ? 'Sim' : 'Não') . "\n";
}

echo "\n--- TRANSIÇÕES ---\n";
$transicoes = EtapaTransicao::with(['etapaOrigem', 'etapaDestino'])->get();
foreach ($transicoes as $t) {
    echo "Origem: " . ($t->etapaOrigem->nome ?? 'N/A') . " (ID: {$t->etapa_origem_id}) -> ";
    echo "Destino: " . ($t->etapaDestino->nome ?? 'N/A') . " (ID: {$t->etapa_destino_id}), ";
    echo "Label: [{$t->label_botao}], Cor: [{$t->cor_botao}], Ativo: " . ($t->ativo ? 'Sim' : 'Não') . "\n";
}
