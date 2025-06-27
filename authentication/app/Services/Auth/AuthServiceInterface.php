<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\InvalidLoginCredentialsException;

interface AuthServiceInterface
{
    /**
     * @throws InvalidLoginCredentialsException
     */
    public function login(string $email, string $password, string $ip, string $userAgent): array;

    /**
     * @throws InvalidTokenException
     */
    public function logout(string $token): void;

    /**
     * @throws InvalidTokenException
     */
    public function refreshToken(string $refreshToken): array;
}
