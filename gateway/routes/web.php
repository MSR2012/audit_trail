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

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Router;

$router->group(['middleware' => 'throttle'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthGatewayController@login');
        $router->post('logout', ['middleware' => 'jwt', 'uses' => 'AuthGatewayController@logout']);
        $router->post('refresh_token', ['middleware' => 'jwt-refresh', 'uses' => 'AuthGatewayController@refreshToken']);
    });

    $router->group(['prefix' => 'app', 'middleware' => 'jwt'], function () use ($router) {
        $router->get('ips', 'AppGatewayController@ips');
    });

    $router->get('/blacklist_token', function (Request $request) use ($router) {
        Cache::put(
            'blacklist_token_' . $request->get('jti'),
            $request->get('jti'),
            Carbon::now()->addMinutes(env('ACCESS_TOKEN_LIFETIME'))
        );

        return response()->json();
    });
});
