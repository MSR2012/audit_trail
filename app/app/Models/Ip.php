<?php

namespace App\Models;

use App\Observers\IpObserver;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string|null $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $label
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Ip extends Model
{
    protected static function boot(): void
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
