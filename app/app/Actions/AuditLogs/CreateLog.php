<?php

namespace App\Actions\AuditLogs;

use App\Dtos\AuditLogs\CreateLogDto;
use App\Models\AuditLog;

class CreateLog
{
    public function execute(CreateLogDto $createLogDto): AuditLog
    {
        $auditLog = new AuditLog();
        $auditLog->user_id = $createLogDto->userId;
        $auditLog->jti = $createLogDto->jti;
        $auditLog->ip_address = $createLogDto->ipAddress;
        $auditLog->action = $createLogDto->action;
        $auditLog->changes = $createLogDto->changes;
        $auditLog->ip = $createLogDto->ip;
        $auditLog->user_agent = $createLogDto->userAgent;
        $auditLog->save();

        return $auditLog;
    }
}
