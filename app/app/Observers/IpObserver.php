<?php

namespace App\Observers;

use App\Actions\AuditLogs\CreateLog;
use App\Constants\AuditLogAction;
use App\Dtos\AuditLogs\CreateLogDto;
use App\Models\Ip;
use Illuminate\Http\Request;

class IpObserver
{
    public function __construct(
        private readonly Request   $request,
        private readonly CreateLog $createLog
    )
    {
    }

    /**
     * Handle the Ip "created" event.
     */
    public function created(Ip $ip): void
    {
        $this->createLog->execute(
            CreateLogDto::createFromArray([
                'user_id' => $this->request->header('at-user-id'),
                'jti' => $this->request->header('at-jti'),
                'ip_address' => $ip->ip_address,
                'action' => AuditLogAction::CREATE,
                'changes' => json_encode([
                    'user_id' => $ip->user_id,
                    'ip_address' => $ip->ip_address,
                    'label' => $ip->label,
                    'comment' => $ip->comment,
                ]),
                'ip' => $this->request->ip(),
                'user_agent' => $this->request->header('User-Agent'),
            ])
        );
    }

    /**
     * Handle the Ip "updated" event.
     */
    public function updated(Ip $ip): void
    {
        $changes = [];
        $original = $ip->getOriginal();
        $changed = $ip->getChanges();
        if (isset($changed['label'])) {
            $changes['label'] = 'FROM ' . $original['label'] . ' to ' . $changed['label'];
        }
        if (isset($changed['comment'])) {
            $changes['comment'] = 'FROM ' . $original['comment'] . ' to ' . $changed['comment'];
        }

        $this->createLog->execute(
            CreateLogDto::createFromArray([
                'user_id' => $this->request->header('at-user-id'),
                'jti' => $this->request->header('at-jti'),
                'ip_address' => $ip->ip_address,
                'action' => AuditLogAction::UPDATE,
                'changes' => json_encode($changes),
                'ip' => $this->request->ip(),
                'user_agent' => $this->request->header('User-Agent'),
            ])
        );
    }

    /**
     * Handle the Ip "deleted" event.
     */
    public function deleted(Ip $ip): void
    {
        $this->createLog->execute(
            CreateLogDto::createFromArray([
                'user_id' => $this->request->header('at-user-id'),
                'jti' => $this->request->header('at-jti'),
                'ip_address' => $ip->ip_address,
                'action' => AuditLogAction::DELETE,
                'changes' => json_encode([]),
                'ip' => $this->request->ip(),
                'user_agent' => $this->request->header('User-Agent'),
            ])
        );
    }
}
