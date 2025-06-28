<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Services\Securities\EncoderInterface',
            'App\Services\Securities\JwtService'
        );
        $this->app->bind(
            'App\Services\Auth\AuthServiceInterface',
            'App\Services\Auth\AuthService'
        );
        $this->app->bind(
            'App\Services\Throttle\ThrottleServiceInterface',
            'App\Services\Throttle\ThrottleService'
        );
    }
}
