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
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Helper\ForwardRequestHelper;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/login', function (Request $request) use ($router) {
        return ForwardRequestHelper::handle(
            $request->method(),
            env('AT_AUTHENTICATION_BASE_URL') . '/authentication/login'
        );
    });
});

$router->group(['prefix' => 'app'], function () use ($router) {
    $router->get('/ips', function (Request $request) use ($router) {
        return ForwardRequestHelper::handle(
            $request->method(),
            env('AT_APP_BASE_URL') . '/app/ips'
        );
    });
});
