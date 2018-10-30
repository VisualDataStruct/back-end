<?php

namespace App\Providers;

use App\Models\ApiToken;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            $token = $request->header('Api-Token', $request->input('token', ''));
            $apiToken = ApiToken::where('token', $token)->first();
            if (!$apiToken) {
                return null;
            }
            if ($apiToken->isExpired) {
                return null;
            }
            $apiToken->addTime();
            $apiToken->save();
            return $apiToken->user;
        });

    }
}
