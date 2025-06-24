<?php

/**
 * @var Router $router
 * @var Request $request
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Helper\ForwardRequestHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Router;

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/login', function (Request $request) use ($router) {
        return ForwardRequestHelper::handle(
            $request->method(),
            env('AT_AUTHENTICATION_BASE_URL') . '/authentication/login'
        );
    });
    $router->group(['middleware' => 'jwt'], function () use ($router) {
        $router->post('/logout', function (Request $request) use ($router) {
            return ForwardRequestHelper::handle(
                $request->method(),
                env('AT_AUTHENTICATION_BASE_URL') . '/authentication/logout'
            );
        });
    });
    $router->group(['middleware' => 'jwt-refresh'], function () use ($router) {
        $router->post('/refresh_token', function (Request $request) use ($router) {
            return ForwardRequestHelper::handle(
                $request->method(),
                env('AT_AUTHENTICATION_BASE_URL') . '/authentication/refresh_token'
            );
        });
    });
});

$router->group(['prefix' => 'app'], function () use ($router) {
    $router->group(['middleware' => 'jwt'], function () use ($router) {
        $router->get('/ips', function (Request $request) use ($router) {
            return ForwardRequestHelper::handle(
                $request->method(),
                env('AT_APP_BASE_URL') . '/app/ips'
            );
        });
    });
});

$router->get('/blacklist_token', function (Request $request) use ($router) {
    Cache::put(
        'blacklist_token_' . $request->get('jti'),
        $request->get('jti'),
        Carbon::now()->addMinutes(env('ACCESS_TOKEN_LIFETIME'))
    );

    return response()->json();
});
