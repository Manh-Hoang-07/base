<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Hàm gọi giao diện đăng ký
     * @return View
     */
    public function index(): View
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
}
