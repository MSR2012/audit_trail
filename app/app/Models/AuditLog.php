<?php

namespace App\Models;


use MongoDB\Laravel\Eloquent\Model;

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
}
