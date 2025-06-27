<?php

namespace App\Dtos\Ips;

class UpdateIpDto
{
    private function __construct(
        public string $label,
        public string $comment,
    )
    {
    }

    public static function createFromArray(array $data): UpdateIpDto
    {
        return new self(
            $data['label'],
            $data['comment'] ?? '',
        );
    }
}
