<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(
        private readonly User $user
    )
    {
    }

    public function getByUserId(string $userId): ?User
    {
        return $this->user->where('id', $userId)->first();
    }

    public function getByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }
}
