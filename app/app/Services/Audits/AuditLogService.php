<?php

namespace App\Services\Audits;

use App\Actions\AuditLogs\CreateLog;
use App\Dtos\AuditLogs\CreateLogDto;
use App\Models\AuditLog;
use App\Repositories\AuditLogRepository;

class AuditLogService implements AuditLogServiceInterface
{
    public function __construct(
        private readonly AuditLogRepository $auditLogRepository,
        private readonly CreateLog          $createLog
    )
    {
    }

    public function index(
        ?int    $userId = null,
        ?string $ipAddress = null,
        ?string $sessionId = null
    ): array
    {
        $auditLogs = $this->auditLogRepository->all($userId, $ipAddress, $sessionId);

        return $auditLogs->map(function (AuditLog $auditLog) {
            return [
                'user_id' => $auditLog->user_id,
                'ip_address' => $auditLog->ip_address,
                'action' => $auditLog->action,
                'changes' => $auditLog->changes,
            ];
        })->toArray();
    }

    public function create(array $data): AuditLog
    {
        return $this->createLog->execute(
            CreateLogDto::createFromArray($data)
        );
    }
}
