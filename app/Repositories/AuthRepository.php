<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function login(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
