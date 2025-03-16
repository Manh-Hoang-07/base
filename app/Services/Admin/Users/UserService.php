<?php

namespace App\Services\Admin\Users;

use App\Repositories\Admin\Users\UserRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use lib\DataTable;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Hàm lấy danh sách người dùng
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->userRepository->getList($filters, $options);
    }

    /**
     * Hàm lấy thông tin tài khoản
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->userRepository->findById($id);
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
            'messages' => 'Thêm mới tài khoản thất bại'
        ];
        $keys = ['name', 'email', 'password'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->userRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới tài khoản thành công';
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
            'messages' => 'Cập nhật tài khoản thất bại'
        ];
        $keys = ['name', 'email'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($user = $this->userRepository->findById($id))
            && $this->userRepository->update($user, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật tài khoản thành công';
        }
        return $return;
    }

    /**
     * Hàm xóa tài khoản
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa tài khoản thất bại'
        ];
        if (($user = $this->userRepository->findById($id))
            && $this->userRepository->delete($user)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa tài khoản thành công';
        }
        return $return;
    }

    /**
     * Hàm đồng bộ lại vai trò của người dùng
     * @param $id
     * @param array $roles
     * @return void
     */
    public function assignRoles($id, array $roles): void
    {
        $user = $this->userRepository->findById($id);
        $user->syncRoles($roles);
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
            'messages' => 'Thay đổi trạng thái tài khoản thất bại'
        ];
        $status = !empty($status) ? 1 : 0;
        if ($user = $this->userRepository->findById($id)) {
            if ((!empty($user->status) && !empty($status))
                || (empty($user->status) && empty($status))
            ) {
                $return['messages'] = 'Trạng thái cần không thay đổi không đúng';
            } elseif ($this->userRepository->update($user, ['status' => $status])) {
                $return['success'] = true;
                $return['messages'] = 'Thay đổi trạng thái tài khoản thành công';
            }
        } else {
            $return['messages'] = 'Tài khoản không hợp lệ';
        }
        return $return;
    }
}
