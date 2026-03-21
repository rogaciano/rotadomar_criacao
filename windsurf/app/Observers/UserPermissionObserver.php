<?php

namespace App\Observers;

use App\Models\UserPermission;

class UserPermissionObserver
{
    public function saved(UserPermission $userPermission): void
    {
        PermissionCacheObserver::clearUserCache($userPermission->user_id);
    }

    public function deleted(UserPermission $userPermission): void
    {
        PermissionCacheObserver::clearUserCache($userPermission->user_id);
    }
}
