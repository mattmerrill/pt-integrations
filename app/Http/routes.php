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

use App\CredentialCollection;
use App\Integration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Infusionsoft\Infusionsoft;
use Infusionsoft\Token;


$app->group(['prefix' => 'v1', 'middleware' => 'api'], function ($app) {
    $app->post('/', function (Request $request) {
        //TODO: Need to handle both the Integration and Credentials.
        $integration = Integration::create($request->input());
        return $integration->id;
    });

    $app->get('/{id}', function($id) {
        $integration = Integration::with('credentials')->findOrFail($id);

        $credentials = new CredentialCollection($integration->credentials);

        switch($integration->type){
            case 'Infusionsoft':
                $expiration = new Carbon($credentials->expiration);
                $expiresIn = Carbon::now()->diffInSeconds($expiration);
                if($expiresIn <= (3600 * 2)){
                    $inf = new Infusionsoft([
                        'clientId' => env('INFUSIONSOFT_CLIENT_ID'),
                        'clientSecret' => env('INFUSIONSOFT_CLIENT_SECRET'),
                        'redirectUri' => env('INFUSIONSOFT_REDIRECT_URI')
                    ]);

                    $inf->setToken(new Token([
                        'access_token' => $credentials->access_token,
                        'refresh_token' => $credentials->refresh_token,
                        'expires_in' => $expiresIn
                    ]));

                    try {
                        $refreshedToken = $inf->refreshAccessToken();

                        $credentials->access_token = $refreshedToken->accessToken;
                        $credentials->refresh_token = $refreshedToken->refreshToken;
                        $credentials->expiration = Carbon::createFromTimestampUTC($refreshedToken->getEndOfLife());
                        $credentials->save();
                    } catch (HttpException $exception) {
//                        if (str_contains($exception->getMessage(), "Bad Request")) {
//                            $account->increment('api_failures');
                            throw $exception;
//                        }
                    }
                }
            break;
        }

        unset($integration->credentials);
        $integration->credentials = $credentials->toArray();

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
