<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateUserRequest;
use App\Services\User\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(): View
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(UpdateUserRequest $request): RedirectResponse
    {
        $result = $this->userService->updateProfile(Auth::user(), $request->validated());
        return redirect()->route('user.profile')->with('success', $result['message']);
    }
}
