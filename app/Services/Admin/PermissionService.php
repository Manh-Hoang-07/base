<?php

namespace App\Services\Admin;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Lấy danh sách tất cả quyền
     */
    public function getAllPermissions()
    {
        return Permission::all();
    }

    /**
     * Tạo mới quyền
     */
    public function createPermission(array $data)
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    /**
     * Lấy thông tin quyền theo ID
     */
    public function getPermissionById($id)
    {
        return Permission::findOrFail($id);
    }

    /**
     * Cập nhật quyền
     */
    public function updatePermission($id, array $data)
    {
        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);

        return $permission;
    }

    /**
     * Xóa quyền
     */
    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        return $permission->delete();
    }
}
