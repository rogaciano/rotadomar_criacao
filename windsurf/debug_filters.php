<?php

// Copie este arquivo para o servidor e execute: php debug_filters.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// ID do usuário para teste (substitua pelo ID correto)
$userId = 2;

// Filtros de teste
$testFilters = [
    'tipo_id' => '3',
    'marca_id' => '9',
    'status_dias' => 'atrasados'
];

echo "Iniciando teste de filtros...\n";

try {
    // Obter usuário
    $user = App\Models\User::find($userId);
    
    if (!$user) {
        echo "ERRO: Usuário com ID {$userId} não encontrado.\n";
        exit(1);
    }
    
    echo "Usuário encontrado: {$user->name} (ID: {$user->id})\n";
    
    // Verificar se a tabela user_filters existe
    $tableExists = DB::select("SHOW TABLES LIKE 'user_filters'");
    echo "Tabela user_filters existe: " . (count($tableExists) > 0 ? "SIM" : "NÃO") . "\n";
    
    if (count($tableExists) > 0) {
        $columns = DB::select("SHOW COLUMNS FROM user_filters");
        echo "Colunas na tabela user_filters:\n";
        foreach ($columns as $column) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
    }
    
    // Limpar filtros existentes
    echo "Limpando filtros existentes...\n";
    $user->clearFilters('movimentacoes');
    
    // Salvar filtros de teste
    echo "Salvando filtros de teste: " . json_encode($testFilters) . "\n";
    $result = App\Models\UserFilter::saveFilters($userId, 'movimentacoes', $testFilters);
    
    echo "Resultado do salvamento:\n";
    echo "- ID: {$result->id}\n";
    echo "- User ID: {$result->user_id}\n";
    echo "- Page Type: {$result->page_type}\n";
    echo "- Filters: " . json_encode($result->filters) . "\n";
    
    // Recuperar filtros
    echo "Recuperando filtros salvos...\n";
    $savedFilters = App\Models\UserFilter::getFilters($userId, 'movimentacoes');
    
    echo "Filtros recuperados: " . json_encode($savedFilters) . "\n";
    
    // Verificar se status_dias está presente
    if (isset($savedFilters['status_dias']) && $savedFilters['status_dias'] === 'atrasados') {
        echo "SUCESSO: O campo 'status_dias' foi salvo e recuperado corretamente.\n";
    } else {
        echo "ERRO: O campo 'status_dias' não foi salvo ou recuperado corretamente.\n";
        
        // Verificar registro direto no banco de dados
        $record = DB::table('user_filters')
            ->where('user_id', $userId)
            ->where('page_type', 'movimentacoes')
            ->first();
        
        if ($record) {
            echo "Registro encontrado no banco de dados:\n";
            echo "- ID: {$record->id}\n";
            echo "- Filters (raw): " . $record->filters . "\n";
            
            // Verificar se é um JSON válido
            $isValidJson = json_decode($record->filters) !== null;
            echo "- É um JSON válido: " . ($isValidJson ? "SIM" : "NÃO") . "\n";
            
            if ($isValidJson) {
                $decodedFilters = json_decode($record->filters, true);
                echo "- Filtros decodificados: " . json_encode($decodedFilters) . "\n";
                echo "- Chaves presentes: " . implode(", ", array_keys($decodedFilters)) . "\n";
            }
        } else {
            echo "Nenhum registro encontrado no banco de dados.\n";
        }
    }
    
    echo "Teste concluído.\n";
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
