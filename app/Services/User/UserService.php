<?php

namespace App\Services\User;

use App\Repositories\User\Users\UserRepository;

class UserService
{
    protected UserRepository $userRepository;

    public function findByEmail(string $email, array $options = [])
    {
        return $this->userRepository->findByEmail($email, $options);
    }
}
