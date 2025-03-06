<?php

namespace App\Repositories\Admin\Permission;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

}
