<?php

namespace App\Services\Audits;

use App\Models\AuditLog;

interface AuditLogServiceInterface
{
    public function index(?int $userId = null, ?string $ipAddress = null, ?string $sessionId = null): array;

    public function create(array $data): AuditLog;
}
