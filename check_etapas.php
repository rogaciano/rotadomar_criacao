<?php
require_once __DIR__ . '/windsurf/vendor/autoload.php';
$app = require_once __DIR__ . '/windsurf/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ETAPAS DE PRODUÇÃO ===\n";
$etapas = \App\Models\EtapaProducao::where('ativo', true)->orderBy('ordem')->get();
foreach ($etapas as $e) {
    echo "ID: {$e->id} | Nome: {$e->nome} | Cor: {$e->cor} | Ativo: {$e->ativo}\n";
}

echo "\n=== TRANSIÇÕES ===\n";
$transicoes = \App\Models\EtapaTransicao::where('ativo', true)->orderBy('etapa_origem_id')->get();
foreach ($transicoes as $t) {
    $origem = \App\Models\EtapaProducao::find($t->etapa_origem_id);
    $destino = \App\Models\EtapaProducao::find($t->etapa_destino_id);
    echo "Origem: {$origem->nome} ({$t->etapa_origem_id}) -> Destino: {$destino->nome} ({$t->etapa_destino_id}) | Botão: {$t->label_botao} | Cor: {$t->cor_botao}\n";
}

echo "\n=== TRANSIÇÕES DA ETAPA 'Operação de Transfers' (ID provável: 5) ===\n";
$etapaTransfers = \App\Models\EtapaProducao::where('nome', 'like', '%Transfers%')->first();
if ($etapaTransfers) {
    echo "Etapa encontrada: ID={$etapaTransfers->id}, Nome={$etapaTransfers->nome}\n";
    $transicoesOrigem = $etapaTransfers->transicoesOrigem()->where('ativo', true)->get();
    echo "Transições de saída: " . $transicoesOrigem->count() . "\n";
    foreach ($transicoesOrigem as $t) {
        echo "  -> {$t->etapaDestino->nome} (Botão: {$t->label_botao}, Cor: {$t->cor_botao})\n";
    }
} else {
    echo "Etapa 'Operação de Transfers' NÃO ENCONTRADA!\n";
}
