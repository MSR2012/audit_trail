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
        $router->get('/', 'IpController@index');
        $router->post('/', 'IpController@store');
        $router->get('/{id}', 'IpController@show');
        $router->group(['middleware' => 'can_edit_ip'], function () use ($router) {
            $router->put('/{id}', 'IpController@update');
        });
        $router->group(['middleware' => 'can_delete_ip'], function () use ($router) {
            $router->delete('/{id}', 'IpController@delete');
        });
    });

    $router->group(['prefix' => 'audit_log'], function () use ($router) {
        $router->get('/', 'AuditLogController@index');
        $router->get('/view-by-user/{changes_within}', 'AuditLogController@viewByLoggedInUser');
        $router->get('/view-by-user/{user_id}/{changes_within}', 'AuditLogController@viewByUser');
        $router->get('/view-by-ip/{ip_address}/{changes_within}', 'AuditLogController@viewByIp');
        $router->post('create', 'AuditLogController@create');
    });
});
