<?php

namespace App\Services\Admin\Permission;

use App\Repositories\Admin\Permission\PermissionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PermissionService
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Lấy danh sách tất cả quyền
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->permissionRepository->getAll($filters, $options);
    }

    /**
     * Tạo mới quyền
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $data = [
            'title' => $data['title'] ?? '',
            'name' => $data['name'] ?? '',
            'guard_name' => $data['guard_name'] ?? 'web',
        ];
        return $this->permissionRepository->create($data);
    }

    /**
     * Lấy thông tin quyền theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->permissionRepository->findById($id);
    }

    /**
     * Cập nhật quyền
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật quyền thất bại'
        ];
        $data = [
            'title' => $data['title'] ?? '',
            'name' => $data['name'] ?? '',
            'guard_name' => $data['guard_name'] ?? 'web',
        ];
        if ($permission = $this->permissionRepository->findById($id)) {
            if (!empty($permission->is_default)) {
                $return['success'] = false;
                $return['messages'] = 'Không thể cập nhật quyền hệ thống';
            } elseif ($this->permissionRepository->update($permission, $data)) {
                $return['success'] = true;
                $return['messages'] = 'Cập nhật quyền thành công';
            }
        }
        return $return;
    }

    /**
     * Xóa quyền
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa quyền thất bại'
        ];
        if ($permission = $this->permissionRepository->findById($id)) {
            if (!empty($permission->is_default)) {
                $return['success'] = false;
                $return['messages'] = 'Không thể xóa quyền hệ thống';
            } elseif ($this->permissionRepository->delete($permission)) {
                $return['success'] = true;
                $return['messages'] = 'Xóa quyền thành công';
            }
        }
        return $return;
    }
}
