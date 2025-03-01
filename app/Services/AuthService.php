<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Service xử lý đăng nhập
     * @param array $credentials
     * @param bool $remember
     * @return array
     */
    public function login(array $credentials, bool $remember): array
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return ['success' => false, 'message' => 'Email và mật khẩu không được để trống.'];
        }
        $return = ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.'];
        if ($this->authRepository->login($credentials, $remember)) {
            $return = ['success' => true, 'message' => 'Đăng nhập thành công!'];
        }
        return $return;
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
        $user = $this->authRepository->register($data);
        if ($user) {
            $return = ['success' => true, 'message' => 'Đăng ký thành công!'];
        }
        return $return;
    }

    /**
     * Service xử lý đăng xuất
     * @return void
     */
    public function logout(): void
    {
        $this->authRepository->logout();
    }
}

