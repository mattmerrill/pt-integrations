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

use Illuminate\Http\Request;


$app->group(['prefix' => 'v1', 'middleware' => 'api'], function ($app) {
    $app->post('/', function (Request $request) {

    });

    $app->get('/{id}', function($id) {

    });

    $app->patch('/{id}', function ($id, Request $request) {

    });


    $app->delete('/{id}', function ($id) {

    });

    $app->get('/{id}/isActive', function () {
        return "true";
    });
});
