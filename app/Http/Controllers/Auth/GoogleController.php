<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\RegisterService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleController extends Controller
{
    protected RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function redirect(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            if ($user = User::where('email', $googleUser->email)->first()) {
                Auth::login($user);
                return redirect()->route('dashboard')->with('success', 'Đăng nhập google thành công');
            }
            $data = [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt('12345678'),
            ];
            $result = $this->registerService->register($data);
            if ($result['success']) {
                return redirect()->route('dashboard')->with('success', $result['message']);
            }
            return redirect()->route('registerForm')->with('error', $result['message']);
        } catch (Exception $e) {
            return redirect()->route('loginForm')->with('error', 'Đăng nhập Google thất bại!');
        }
    }
}
