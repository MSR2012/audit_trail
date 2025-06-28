<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @method Builder|static userId(?int $userId)
 * @method Builder|static ipAddress(?string $ipAddress)
 * @method Builder|static session(?string $sessionId)
 */
class AuditLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'jti', 'ip_address', 'action', 'changes', 'ip', 'user_agent',
    ];

    public function scopeUserId(Builder $query, ?int $userId): Builder
    {
        return $query->when(!empty($userId), function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    public function scopeIpAddress(Builder $query, ?string $ipAddress): Builder
    {
        return $query->when(!empty($ipAddress), function ($query) use ($ipAddress) {
            $query->where('ip_address', $ipAddress);
        });
    }

    public function scopeSession(Builder $query, ?string $sessionId): Builder
    {
        return $query->when(!empty($sessionId), function ($query) use ($sessionId) {
            $query->where('jti', $sessionId);
        });
    }
}
