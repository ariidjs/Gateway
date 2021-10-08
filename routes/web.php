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
    $router->get('products', 'ProductController@getProductStore');
    $router->post('update[/{id}]', 'StoreController@update');
    $router->post('changepassword[/{id}]', 'StoreController@changePassword');
    $router->post('login', 'AuthController@storeLogin');
    $router->delete('[{id}]', 'StoreController@delete');
});

$router->group(['prefix'=>'customer'],function() use($router){
    $router->post('','UsersController@register');
    $router->post('/check','UsersController@getUser');
    // $router->get('store/[{id}]','ProductController@getProductStore');
    // $router->post('list','ProductController@getListProduct');
    $router->post('update[/{id}]','UsersController@updateUser');
    // $router->delete('[{id}]','ProductController@delete');
});

// $router->group(['prefix'=>'products'],function() use($router){
//     $router->post('','ProductController@insert');
//     $router->get('[{id}]','ProductController@getProduct');
//     $router->get('store/[{id}]','ProductController@getProductStore');
//     $router->post('list','ProductController@getListProduct');
//     $router->post('update[/{id}]','ProductController@update');
//     $router->delete('[{id}]','ProductController@delete');
// });

