<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$senha = '@Senha123';
$hash = Hash::make($senha);
$count = User::query()->update(['password' => $hash]);

echo "Senhas atualizadas: {$count} usuários\n";
echo "Senha: {$senha}\n\n";

$users = User::with('localizacao:id,nome')
    ->orderBy('name')
    ->get(['id', 'name', 'email', 'localizacao_id']);

foreach ($users as $u) {
    $loc = $u->localizacao?->nome ?? '-';
    echo sprintf("%-4d %-25s %-25s %s\n", $u->id, $u->name, $u->email, $loc);
}
