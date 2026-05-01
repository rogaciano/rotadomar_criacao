<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public const CRIACAO_STATUSES = [
        'AGUARDANDO ENVIO',
        'EM CRIAÇÃO',
        'DISPONIVEL',
        'AGUARDANDO DESENVOLVIMENTO',
    ];

    public function run(): void
    {
        foreach (self::CRIACAO_STATUSES as $descricao) {
            Status::updateOrCreate(
                ['descricao' => $descricao],
                [
                    'descricao' => $descricao,
                    'ativo' => true,
                ]
            );
        }
    }
}
