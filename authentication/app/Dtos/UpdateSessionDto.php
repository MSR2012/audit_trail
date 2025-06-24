<?php

namespace App\Dtos;

class UpdateSessionDto
{
    private function __construct(
        public string $token,
        public string $token_expires_at,
    )
    {
    }

    public static function createFromArray(array $data): UpdateSessionDto
    {
        return new self(
            $data['token'] ?? '',
            $data['token_expires_at'] ?? '',
        );
    }
}
