<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    /**
     * Xử lý đăng nhập
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function login(array $credentials, bool $remember): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Xử lý đăng ký
     * @param array $data
     * @return User|null
     */
    public function register(array $data): ?User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        if ($user) {
            Auth::login($user);
        }
        return $user;
    }

    /**
     * Xử lý đăng xuất
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
