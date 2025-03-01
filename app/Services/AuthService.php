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
        if ($this->authRepository->login($credentials, $remember)) {
            return ['success' => true, 'message' => 'Đăng nhập thành công!'];
        }
        return ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.'];
    }

    /**
     * Service xử lý đăng ký
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        $user = $this->authRepository->register($data);
        if ($user) {
            return ['success' => true, 'message' => 'Đăng ký thành công!'];
        }
        return ['success' => false, 'message' => 'Đăng ký thất bại. Vui lòng thử lại!'];
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

