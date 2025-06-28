<?php

namespace App\Services\Audits;

use App\Models\AuditLog;

interface AuditLogServiceInterface
{
    public function index(?int $userId): array;

    public function create(array $data): AuditLog;
}
