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

    public function login(array $credentials): array
    {
        if ($this->authRepository->login($credentials)) {
            return ['success' => true, 'message' => 'Đăng nhập thành công!'];
        }

        return ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.'];
    }

    public function logout(): void
    {
        $this->authRepository->logout();
    }
}

