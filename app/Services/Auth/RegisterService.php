<?php

namespace App\Services\Auth;

use App\Repositories\Auth\RegisterRepository;

class RegisterService
{
    protected RegisterRepository $registerRepository;

    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    /**
     * Service xử lý đăng ký
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin.'];
        }
        $return = ['success' => false, 'message' => 'Đăng ký thất bại. Vui lòng thử lại!'];
        $user = $this->registerRepository->register($data);
        if ($user) {
            $return = ['success' => true, 'message' => 'Đăng ký thành công!'];
        }
        return $return;
    }
}

