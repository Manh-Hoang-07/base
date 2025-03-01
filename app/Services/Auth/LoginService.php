<?php

namespace App\Services\Auth;

use App\Repositories\Auth\LoginRepository;

class LoginService
{
    protected LoginRepository $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
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
        if ($this->loginRepository->login($credentials, $remember)) {
            $return = ['success' => true, 'message' => 'Đăng nhập thành công!'];
        }
        return $return;
    }

    /**
     * Service xử lý đăng xuất
     * @return void
     */
    public function logout(): void
    {
        $this->loginRepository->logout();
    }
}

