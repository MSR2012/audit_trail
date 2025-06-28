<?php

namespace App\Repositories;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Collection;

class AuditLogRepository
{
    public function __construct(private readonly AuditLog $auditLog)
    {
    }

    public function all(?int $userId = null, ?string $ipAddress = null, ?string $sessionId = null): Collection
    {
        return $this->auditLog->newModelQuery()
            ->userId($userId)
            ->ipAddress($ipAddress)
            ->session($sessionId)
            ->get();
    }
}
