<?php

namespace App\Repositories\Admin\Role;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    /**
     * Lấy tất cả vai trò kèm quyền
     */
    public function getAllRoles()
    {
        return Role::with('permissions')->get();
    }

    /**
     * Lấy thông tin 1 vai trò theo ID
     */
    public function getRoleById($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    /**
     * Tạo mới vai trò và gán quyền
     */
    public function createRole(array $data)
    {
        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role;
    }

    /**
     * Cập nhật thông tin vai trò
     */
    public function updateRole($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $data['name'] ?? '', 'title' => $data['title'] ?? '']);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        } else {
            $role->syncPermissions([]); // Xóa hết quyền nếu không có quyền nào được chọn
        }
        return $role;
    }

    /**
     * Xóa vai trò
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }

    /**
     * Lấy tất cả quyền
     */
    public function getAllPermissions()
    {
        return Permission::all();
    }
}
