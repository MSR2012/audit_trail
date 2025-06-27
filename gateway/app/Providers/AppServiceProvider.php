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
    public function register()
    {
        $this->app->when('App\Http\Controllers\AuthGatewayController')
            ->needs('App\Services\Gateways\GatewayServiceInterface')
            ->give('App\Services\Gateways\AuthGatewayService');

        $this->app->when('App\Http\Controllers\AppGatewayController')
            ->needs('App\Services\Gateways\GatewayServiceInterface')
            ->give('App\Services\Gateways\AppGatewayService');

        $this->app->bind(
            'App\Services\Securities\DecoderInterface',
            'App\Services\Securities\JwtService'
        );
    }
}
