<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Observer para invalidar o cache de permissões do usuário
 * quando UserPermission ou Group são alterados.
 */
class PermissionCacheObserver
{
    /**
     * Limpa o cache de permissões de um usuário específico.
     */
    public static function clearUserCache(int $userId): void
    {
        Cache::forget("user:{$userId}:permissions");
    }

    /**
     * Limpa o cache de permissões de todos os usuários de um grupo.
     */
    public static function clearGroupUsersCache($group): void
    {
        if ($group && $group->users) {
            foreach ($group->users as $user) {
                static::clearUserCache($user->id);
            }
        }
    }
}
