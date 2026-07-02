<?php

namespace App\Observers;

use App\Models\Group;

class GroupObserver
{
    public function saved(Group $group): void
    {
        PermissionCacheObserver::clearGroupUsersCache($group);
    }

    public function deleted(Group $group): void
    {
        PermissionCacheObserver::clearGroupUsersCache($group);
    }

    /**
     * Quando as permissões do grupo são sincronizadas (pivot),
     * este método deve ser chamado manualmente no controller.
     */
    public function pivotSynced(Group $group): void
    {
        PermissionCacheObserver::clearGroupUsersCache($group);
    }
}
