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
use Laravel\Lumen\Routing\Router;

$router->group(['middleware' => 'throttle'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthGatewayController@login');
        $router->post('logout', ['middleware' => 'jwt', 'uses' => 'AuthGatewayController@logout']);
        $router->post('refresh_token', ['middleware' => 'jwt-refresh', 'uses' => 'AuthGatewayController@refreshToken']);
    });

    $router->group(['prefix' => 'app', 'middleware' => 'jwt'], function () use ($router) {
        $router->group(['prefix' => 'ips'], function () use ($router) {
            $router->get('/', 'AppGatewayController@ipIndex');
            $router->post('/', 'AppGatewayController@ipStore');
            $router->get('/{id}', 'AppGatewayController@ipShow');
            $router->put('/{id}', 'AppGatewayController@ipUpdate');
            $router->delete('/{id}', 'AppGatewayController@ipDelete');
        });

        $router->group(['prefix' => 'audit_log'], function () use ($router) {
            $router->get('/', 'AppGatewayController@auditLogIndex');
            $router->get('/view-by-user/{changes_within}', 'AppGatewayController@auditLogViewByLoggedInUser');
            $router->get('/view-by-user/{user_id}/{changes_within}', 'AppGatewayController@auditLogViewByUser');
            $router->get('/view-by-ip/{ip_address}/{changes_within}', 'AppGatewayController@auditLogViewByIp');
        });
    });

    $router->get('blacklist_token', 'TokenController@putInBlacklist');
});
