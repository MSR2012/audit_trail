<?php

namespace App\Models;

use App\Observers\IpObserver;
use MongoDB\Laravel\Eloquent\Model;

class Ip extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($ip) {
            app(IpObserver::class)->created($ip);
        });

        static::updated(function ($ip) {
            app(IpObserver::class)->updated($ip);
        });

        static::deleted(function ($ip) {
            app(IpObserver::class)->deleted($ip);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'ip_address', 'label', 'comment',
    ];
}
