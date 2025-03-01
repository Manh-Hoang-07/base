<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    protected RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
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
        $result = $this->registerService->register($request->validated());
        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        }
        return redirect()->route('registerForm')->with('error', $result['message']);
    }
}
