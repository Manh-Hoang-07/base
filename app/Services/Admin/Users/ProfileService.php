<?php

namespace App\Services\Admin\Users;

use App\Repositories\Admin\Users\ProfileRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class ProfileService
{
    protected ProfileRepository $profileRepository;
    protected UserService $userService;

    public function __construct(ProfileRepository $profileRepository, UserService $userService)
    {
        $this->profileRepository = $profileRepository;
        $this->userService = $userService;
    }

    /**
     * Hàm lấy danh sách hồ sơ
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->profileRepository->getList($filters, $options);
    }

    /**
     * Hàm lấy thông tin hồ sơ
     * @param $user_id
     * @return Model|null
     */
    public function findByUserId($user_id): ?Model
    {
        return $this->profileRepository->findOne(['user_id' => $user_id]);
    }

    /**
     * Hàm cập nhật hồ sơ
     * @param $user_id
     * @param array $data
     * @return array
     */
    public function update($user_id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật hồ sơ thất bại'
        ];
        $keys = ['address', 'phone', 'birth_date', 'gender'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($user_id)
            && !empty($updateData)
            && $this->userService->findById($user_id)
            && $this->profileRepository->updateProfile($user_id, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật hồ sơ thành công';
        }
        return $return;
    }
}
