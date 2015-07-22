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

use App\Integration;
use Illuminate\Http\Request;


$app->group(['prefix' => 'v1', 'middleware' => 'api'], function ($app) {
    $app->post('/', function (Request $request) {
        $integration = Integration::create($request->input());
        return $integration->id;
    });

    $app->get('/{id}', function($id) {
        $integration = Integration::with('credentials')->findOrFail($id);
        //TODO: Per Integration, determine if expired, refresh if necessary/able.
        return $integration;
    });

    $app->patch('/{id}', function ($id, Request $request) {
        $integration = Integration::findOrFail($id);
        return $integration->update($request->input());
    });

    $app->delete('/{id}', function ($id) {
        $integration = Integration::findOrFail($id);
        return $integration->delete();
    });
});
