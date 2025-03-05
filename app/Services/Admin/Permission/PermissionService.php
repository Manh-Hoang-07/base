<?php

namespace App\Services\Admin\Permission;

use App\Repositories\Admin\Permission\PermissionRepository;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository) {
        $this->permissionRepository = $permissionRepository;
    }
    /**
     * Lấy danh sách tất cả quyền
     */
    public function getAllPermissions()
    {
        return $this->permissionRepository->getAll();
    }

    /**
     * Tạo mới quyền
     */
    public function createPermission(array $data)
    {
        return $this->permissionRepository->create($data);
    }

    /**
     * Lấy thông tin quyền theo ID
     */
    public function getPermissionById($id)
    {
        return $this->permissionRepository->findById($id);
    }

    /**
     * Cập nhật quyền
     */
    public function updatePermission($id, array $data)
    {
        return $this->permissionRepository->update($id, $data);
    }

    /**
     * Xóa quyền
     */
    public function deletePermission($id)
    {
        return $this->permissionRepository->delete($id);
    }
}
