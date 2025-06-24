<?php

namespace App\Dtos;

use Illuminate\Support\Carbon;

class CreateSessionDto
{
    private function __construct(
        public int    $user_id,
        public string $uuid,
        public string $ip_address,
        public string $user_agent,
        public string $token,
        public string $token_expires_at,
        public string $refresh_token,
        public string $refresh_token_expires_at
    )
    {
    }

    public static function createFromArray(array $data): CreateSessionDto
    {
        return new self(
            $data['user_id'] ?? 0,
            $data['uuid'] ?? '',
            $data['ip_address'] ?? '',
            $data['user_agent'] ?? '',
            $data['token'] ?? '',
            $data['token_expires_at'] ?? Carbon::now(),
            $data['refresh_token'] ?? '',
            $data['refresh_token_expires_at'] ?? Carbon::now()
        );
    }
}
