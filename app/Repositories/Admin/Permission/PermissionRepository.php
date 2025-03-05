<?php

namespace App\Repositories\Admin\Permission;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    /**
     * Lấy danh sách tất cả quyền
     */
    public function getAll()
    {
        return Permission::get();
    }

    /**
     * Tạo mới quyền
     */
    public function create(array $data)
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    /**
     * Lấy quyền theo ID
     */
    public function findById($id)
    {
        return Permission::findOrFail($id);
    }

    /**
     * Cập nhật quyền
     */
    public function update($id, array $data)
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
    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        return $permission->delete();
    }
}
