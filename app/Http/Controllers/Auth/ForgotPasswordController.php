<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Otp\OtpService;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    protected OtpService $otpService;
    protected ForgotPasswordService $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    /**
     * Hàm gọi giao diện quên mật khẩu
     * @return View
     */
    public function index(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Hàm gửi OTP quên mật khẩu về email
     * @param Request $request
     * @return JsonResponse
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        return response()->json($this->forgotPasswordService->sendOtp($request->email ?? ''));
    }

    /**
     * Hàm đặt lại mật khẩu
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);
        return response()->json($this->forgotPasswordService->resetPassword($request->all()));
    }
}
