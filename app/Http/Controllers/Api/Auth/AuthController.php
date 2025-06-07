<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Đăng nhập (API)
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());
        
        if ($result['success']) {
            return $this->successResponse([
                'user' => $result['user'],
                'token' => $result['token'],
                'token_type' => 'Bearer'
            ], 'Đăng nhập thành công');
        }
        
        return $this->errorResponse($result['message'] ?? 'Đăng nhập thất bại', null, 401);
    }

    /**
     * Đăng ký (API)
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        
        if ($result['success']) {
            return $this->successResponse([
                'user' => $result['user'],
                'token' => $result['token'] ?? null,
                'token_type' => 'Bearer'
            ], $result['message'] ?? 'Đăng ký thành công', 201);
        }
        
        return $this->errorResponse($result['message'] ?? 'Đăng ký thất bại');
    }

    /**
     * Đăng xuất (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse(null, 'Đăng xuất thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Đăng xuất thất bại');
        }
    }

    /**
     * Lấy thông tin user hiện tại (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        return $this->successResponse($request->user(), 'Lấy thông tin user thành công');
    }

    /**
     * Refresh token (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Xóa token cũ
            $request->user()->currentAccessToken()->delete();
            
            // Tạo token mới
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return $this->successResponse([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 'Refresh token thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Refresh token thất bại');
        }
    }

    /**
     * Quên mật khẩu (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $result = $this->authService->forgotPassword($request->email);
        
        if ($result['success']) {
            return $this->successResponse(null, $result['message'] ?? 'Đã gửi email reset mật khẩu');
        }
        
        return $this->errorResponse($result['message'] ?? 'Gửi email reset mật khẩu thất bại');
    }

    /**
     * Reset mật khẩu (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        $result = $this->authService->resetPassword($request->validated());
        
        if ($result['success']) {
            return $this->successResponse(null, $result['message'] ?? 'Reset mật khẩu thành công');
        }
        
        return $this->errorResponse($result['message'] ?? 'Reset mật khẩu thất bại');
    }
}
