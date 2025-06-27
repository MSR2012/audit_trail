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

$router->group(['prefix' => 'app'], function () use ($router) {
    $router->group(['prefix' => 'ips'], function () use ($router) {
        $router->get('/', function () use ($router) {
            return response()->json(['message' => 'All ips'], 200);
        });
    });

    $router->group(['prefix' => 'audit_log'], function () use ($router) {
        $router->get('view-by-user/{changes_made_within}', 'AuditLogController@viewByUser');
        $router->get('view-by-ip/{ip_address}/{changes_made_within}', 'AuditLogController@viewByUser');
        $router->post('create', 'AuditLogController@create');
    });
});
