<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Ip extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'ip_address', 'label', 'comment',
    ];
}
