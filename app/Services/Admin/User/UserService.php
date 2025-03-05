<?php

namespace App\Services\Admin\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function list(array $filters = [], int $perPage = 20, string $sortBy = 'id', string $sortOrder = 'asc'): LengthAwarePaginator
    {
        $query = User::query();
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }
        if (!empty($filters) && !$query->exists()) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }
        return $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'roles' => $data['roles'] ?? '',
            'password' => Hash::make($data['password'] ?? '12345678'),
        ]);
    }

    public function update(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        return $user;
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
