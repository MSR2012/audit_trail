<?php

namespace App\Dtos\Ips;

class CreateIpDto
{
    private function __construct(
        public int    $userId,
        public string $ipAddress,
        public string $label,
        public string $comment
    )
    {
    }

    public static function createFromArray(array $data): CreateIpDto
    {
        return new self(
            $data['user_id'],
            $data['ip_address'],
            $data['label'],
            $data['comment'] ?? '',
        );
    }
}
