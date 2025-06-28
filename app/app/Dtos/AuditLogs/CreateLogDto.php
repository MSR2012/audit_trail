<?php

namespace App\Dtos\AuditLogs;

class CreateLogDto
{
    private function __construct(
        public int    $userId,
        public string $jti,
        public string $ipAddress,
        public string $action,
        public string $changes,
        public string $ip,
        public string $userAgent
    )
    {
    }

    public static function createFromArray(array $data): CreateLogDto
    {
        return new self(
            $data['user_id'],
            $data['jti'],
            $data['ip_address'],
            $data['action'],
            $data['changes'],
            $data['ip'],
            $data['user_agent']
        );
    }
}
