<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->group(['prefix' => 'authentication'], function () use ($router) {
    $router->post('/login', 'AuthController@login');
    $router->post('/refresh_token', 'AuthController@refreshToken');
    $router->post('/logout', 'AuthController@logout');
});
