<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Drivers
$router->group(['prefix' => 'drivers'], function () use ($router) {
    $router->post('signup', 'AuthController@signUpDriver');
    $router->post('login', 'AuthController@loginDriver');
    $router->get('profile/{id}', 'DriversController@getDriver');
    $router->get('test', 'AuthController@testGuzzel');
});

//Stores
$router->group(['prefix' => 'stores'], function () use ($router) {
    $router->post('signup', 'AuthController@signUpStore');
    $router->get('[{id}]', 'StoreController@getStore');
    $router->post('update[/{id}]', 'StoreController@update');
    $router->post('login', 'AuthController@storeLogin');
    $router->delete('[{id}]', 'StoreController@delete');
});
