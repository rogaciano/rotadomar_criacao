<?php

namespace App\Policies;

use App\Models\Produto;
use App\Models\User;

class ProdutoPolicy
{
    public function editObsDesigner(User $user, Produto $produto): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return (int) ($user->estilista?->id ?? 0) === (int) $produto->estilista_id;
    }
}
