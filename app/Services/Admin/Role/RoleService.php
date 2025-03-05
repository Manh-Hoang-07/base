<?php

namespace App\Services\Admin\Role;

use App\Repositories\Admin\Role\RoleRepository;

class RoleService
{
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Lấy danh sách vai trò
     */
    public function getAllRoles()
    {
        return $this->roleRepository->getAllRoles();
    }

    /**
     * Lấy thông tin vai trò theo ID
     */
    public function getRoleById($id)
    {
        return $this->roleRepository->getRoleById($id);
    }

    /**
     * Lấy danh sách quyền
     */
    public function getAllPermissions()
    {
        return $this->roleRepository->getAllPermissions();
    }

    /**
     * Xử lý tạo vai trò
     */
    public function createRole(array $data)
    {
        return $this->roleRepository->createRole($data);
    }

    /**
     * Xử lý cập nhật vai trò
     */
    public function updateRole($id, array $data)
    {
        return $this->roleRepository->updateRole($id, $data);
    }

    /**
     * Xử lý xóa vai trò
     */
    public function deleteRole($id)
    {
        return $this->roleRepository->deleteRole($id);
    }
}
