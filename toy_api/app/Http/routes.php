<?php

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

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->group(['namespace' => 'App\Http\Controllers\API', 'prefix' => 'api/v1'], function() use($app)
{
    $app->get('products', 'ProductsAPIController@index');
    $app->get('products/{id}', 'ProductsAPIController@show');
    $app->post('products', 'ProductsAPIController@store');
    $app->put('products/{id}', 'ProductsAPIController@update');
    $app->delete('products/{id}', 'ProductsAPIController@destroy');
});
