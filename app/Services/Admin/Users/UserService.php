<?php

namespace App\Services\Admin\Users;

use App\Repositories\Admin\Users\UserRepository;
use App\Services\BaseService;

class UserService extends BaseService
{
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    protected function getRepository(): UserRepository
    {
        return $this->repository;
    }



    /**
     * Service xử lý tạo tài khoản
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'message' => 'Thêm mới tài khoản thất bại'
        ];

        $keys = ['email', 'password', 'status'];
        $insertData = DataTable::getChangeData($data, $keys);

        // Hash password
        if (isset($insertData['password'])) {
            $insertData['password'] = bcrypt($insertData['password']);
        }

        // Set default status
        if (!isset($insertData['status'])) {
            $insertData['status'] = 'active';
        }

        $user = $this->getRepository()->create($insertData);
        if ($user) {
            // Assign roles if provided
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            $return['success'] = true;
            $return['message'] = 'Thêm mới tài khoản thành công';
            $return['data'] = $this->getRepository()->findById($user->id);
        }

        return $return;
    }

    /**
     * Hàm cập nhật tài khoản
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'message' => 'Cập nhật tài khoản thất bại'
        ];

        $user = $this->getRepository()->findById($id);
        if (!$user) {
            $return['message'] = 'Tài khoản không tồn tại';
            return $return;
        }

        $keys = ['email', 'password', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);

        // Hash password if provided
        if (isset($updateData['password']) && !empty($updateData['password'])) {
            $updateData['password'] = bcrypt($updateData['password']);
        } else {
            unset($updateData['password']); // Don't update password if empty
        }

        if ($this->getRepository()->update($user, $updateData)) {
            // Update roles if provided
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            $return['success'] = true;
            $return['message'] = 'Cập nhật tài khoản thành công';
            $return['data'] = $this->getRepository()->findById($id);
        }

        return $return;
    }

    /**
     * Hàm đồng bộ lại vai trò của người dùng
     * @param $id
     * @param array $roles
     * @return array
     */
    public function assignRoles($id, array $roles): array
    {
        $return = [
            'success' => false,
            'message' => 'Phân quyền thất bại'
        ];

        $user = $this->getRepository()->findById($id);
        if (!$user) {
            $return['message'] = 'Tài khoản không tồn tại';
            return $return;
        }

        try {
            $user->syncRoles($roles);
            $return['success'] = true;
            $return['message'] = 'Phân quyền thành công';
            $return['data'] = $this->getRepository()->findById($id);
        } catch (\Exception $e) {
            $return['message'] = 'Phân quyền thất bại: ' . $e->getMessage();
        }

        return $return;
    }

    /**
     * Hàm thay đổi trạng thái tài khoản
     * @param $id
     * @param int $status
     * @return array
     */
    public function changeStatus($id, int $status = 0): array
    {
        $return = [
            'success' => false,
            'message' => 'Thay đổi trạng thái tài khoản thất bại'
        ];

        $user = $this->getRepository()->findById($id);
        if (!$user) {
            $return['message'] = 'Tài khoản không hợp lệ';
            return $return;
        }

        // Chuẩn hóa status
        $newStatus = !empty($status) ? 'active' : 'inactive';

        if ($this->getRepository()->update($user, ['status' => $newStatus])) {
            $return['success'] = true;
            $return['message'] = 'Thay đổi trạng thái tài khoản thành công';
            // Trả về user data với roles
            $return['data'] = $this->getRepository()->findById($id);
        }

        return $return;
    }

    /**
     * Hàm lấy ra danh sách người dùng theo từ
     * @param string $term
     * @param string $column
     * @param int $limit
     * @param string $idField
     * @param string $nameField
     * @return array
     */
    public function autocomplete(string $term = '', string $column = 'email', int $limit = 10, string $idField = 'id', string $nameField = 'email'): array
    {
        return parent::autocomplete($term, $column, $limit, $idField, $nameField);
    }
}
