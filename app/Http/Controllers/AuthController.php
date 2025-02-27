<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Hiển thị form login
    public function loginForm(): View|Application|Factory
    {
        return view('auth.login');
    }

    // Xử lý login
    public function login(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->login($request->validated());

        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        }
        return redirect()->route('loginForm')->with('error', $result['message']);
    }

    // Đăng xuất
    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        Session::flush();
        return redirect()->route('loginForm')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
