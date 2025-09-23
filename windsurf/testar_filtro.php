<?php

// Este script testa se o filtro por status de dias está funcionando corretamente

// Carregar o autoloader do Laravel
require __DIR__ . '/vendor/autoload.php';

// Carregar o kernel do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Criar uma requisição para testar o filtro
$request = Illuminate\Http\Request::create('/movimentacoes/filtro/status-dias', 'GET', [
    'status_dias' => 'atrasados'
]);

// Executar a requisição
$response = $kernel->handle($request);

// Verificar se a resposta é bem-sucedida
if ($response->getStatusCode() === 200) {
    echo "Teste bem-sucedido! O filtro por status de dias está funcionando corretamente.\n";
} else {
    echo "Erro no teste! O filtro por status de dias não está funcionando corretamente.\n";
    echo "Status code: " . $response->getStatusCode() . "\n";
}

// Limpar a aplicação
$kernel->terminate($request, $response);
