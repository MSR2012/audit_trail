<?php

namespace App\Repositories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Collection;

class SessionRepository
{
    public function __construct(private readonly Session $session)
    {
    }

    public function allByUserId(string $userId): Collection
    {
        return $this->session->where('user_id', $userId)->get();
    }

    public function getByToken(string $token): ?Session
    {
        return $this->session->where('token', $token)->first();
    }

    public function getByRefreshToken(string $refreshToken): ?Session
    {
        return $this->session->where('refresh_token', $refreshToken)->first();
    }
}
