<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateProfile(User $user, array $data): array
    {
        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }
        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return ['success' => true, 'message' => 'Cập nhật tài khoản thành công.'];
    }
}
