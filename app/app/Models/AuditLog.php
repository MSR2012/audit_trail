<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property int|null $id
 * @property int|null $user_id
 * @property string|null $user_name
 * @property string|null $jti
 * @property string|null $ip_address
 * @property int|null $action
 * @property string|null $changes
 * @property string|null $ip
 * @property string|null $user_agent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
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
        'user_id', 'user_name', 'jti', 'ip_address', 'action', 'changes', 'ip', 'user_agent',
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
