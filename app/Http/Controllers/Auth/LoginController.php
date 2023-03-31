<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Services\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Auth;
use App\Services\Cache\Firebase as FirebaseCache;
use App\Services\Tomoni\Models\Auth\Me;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Auth::shouldUse('web');
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $user = $this->resolveUser($request->id_token);

        if ($user) {
            Auth::login($user);
            return true;
        }
        return false;
    }

    protected function resolveUser($token)
    {
        if ((bool)env('THIS_IS_AUTH', false)) {
            return FirebaseAuth::user($token);
        }

        request()->headers->set('X-Firebase-IDToken', $token);
        return FirebaseCache::getOrPut($token, new Me);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);
    }
}
