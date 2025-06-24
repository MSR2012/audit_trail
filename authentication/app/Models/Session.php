<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $user_id
 * @property string|null $uuid
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $token
 * @property Carbon|null $token_expires_at
 * @property string|null $refresh_token
 * @property Carbon|null $refresh_token_expires_at
 *
 * @mixin Builder
 */
class Session extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'ip_address',
        'user_agent',
        'token',
        'token_expires_at',
        'refresh_token',
        'refresh_token_expires_at',
    ];
}
