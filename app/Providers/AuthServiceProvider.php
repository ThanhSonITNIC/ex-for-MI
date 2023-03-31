<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\Firebase\Auth as FirebaseAuth;
use App\Services\Tomoni\Models\Auth\Me;
use App\Services\Tomoni\Models\Auth\Tomoni;
use Illuminate\Auth\AuthenticationException;
use App\Services\Cache\Firebase as FirebaseCache;
use App\Services\Cache\Tomoni as TomoniCache;
use App\Services\Tomoni\Authentication\UserTomoniProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerDrivers();
    }

    public function registerDrivers()
    {
        Auth::viaRequest('firebase', function ($request) {
            try {
                Auth::shouldUse('firebase');
                $token = $request->header('X-Firebase-IDToken');

                if (!$token) {
                    return null;
                }

                if ((bool)env('THIS_IS_AUTH', false)) {
                    return FirebaseAuth::user($token);
                }

                if (FirebaseCache::has($token)) {
                    return FirebaseCache::get($token);
                }

                return FirebaseCache::getOrPut($token, new Me);
            } catch (\Throwable $th) {
                throw new AuthenticationException($th->getMessage());
            }
        });

        Auth::viaRequest('tomoni', function ($request) {
            try {
                Auth::shouldUse('tomoni');
                $token = $request->bearerToken();

                if (TomoniCache::has($token)) {
                    return TomoniCache::get($token);
                }

                return TomoniCache::getOrPut($token, new Tomoni);
            } catch (\Throwable $th) {
                throw new AuthenticationException($th->getMessage());
            }
        });

        Auth::provider('user_tomoni_provider', function ($app, array $config) {
            return new UserTomoniProvider();
        });
    }
}
