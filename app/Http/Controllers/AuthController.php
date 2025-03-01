<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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

    /**
     * Hàm gọi giao diện đăng nhập
     * @return View|Application|Factory
     */
    public function loginForm(): View|Application|Factory
    {
        return view('auth.login');
    }

    /**
     * Hàm xử lý đăng nhập
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        $remember = $request->has('remember');
        $result = $this->authService->login($credentials, $remember);
        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        }
        return redirect()->route('loginForm')->with('error', $result['message']);
    }

    /**
     * Hàm gọi giao diện đăng ký
     * @return View
     */
    public function registerForm(): View
    {
        return view('auth.register');
    }

    /**
     * Hàm xử lý đăng ký
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $result = $this->authService->register($request->validated());
        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        }
        return redirect()->route('registerForm')->with('error', $result['message']);
    }

    /**
     * Hàm xử lý đăng xuất
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        Session::invalidate();
        return redirect()->route('loginForm')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
