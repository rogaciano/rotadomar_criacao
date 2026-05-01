<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'produtos', 'display_name' => 'Produtos', 'description' => 'Produtos'],
            ['name' => 'cadastros', 'display_name' => 'Cadastros', 'description' => 'Cadastros gerais'],
            ['name' => 'consultas', 'display_name' => 'Consultas', 'description' => 'Consultas'],
            ['name' => 'movimentacoes', 'display_name' => 'Movimentações', 'description' => 'Movimentações'],
            ['name' => 'kanban', 'display_name' => 'Kanban', 'description' => 'Kanban'],
            ['name' => 'planejamento', 'display_name' => 'Planejamento', 'description' => 'Planejamento de capacidade'],
            ['name' => 'sugestoes', 'display_name' => 'Sugestões', 'description' => 'Sugestões'],
            ['name' => 'logistica', 'display_name' => 'Logística', 'description' => 'Logística'],
            ['name' => 'criacao', 'display_name' => 'Criação', 'description' => 'Módulo de Criação'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
