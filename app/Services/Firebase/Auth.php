<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Auth\UserRecord;
use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

class Auth
{
    public static function user(string $idToken): User
    {
        $user = Cache::user($idToken);

        if (!$user) {
            $verifiedIdToken = Firebase::auth()->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            $expire = \Carbon\Carbon::createFromTimestamp($verifiedIdToken->claims()->get('exp')->getTimestamp());
            $user = static::resolveUser(Firebase::auth()->getUser($uid));
            Cache::user($idToken, $user, $expire);
        }

        return $user;
    }

    public static function resolveUser(UserRecord $data): User
    {
        $uid = $data->uid;
        $email = $data->email;
        $disabled = $data->disabled;
        $emailVerified = $data->emailVerified;

        $user = User::where('provider_uid', $uid)->first();

        if ($user && $email != $user->email) {
            $user->update(['email' => $email]);
        }

        if (!$user && $email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update(['provider_uid' => $uid]);
            }
        }

        if (!$user) {
            $user = User::create([
                'name' => $data->displayName,
                'tel' => $data->phoneNumber,
                'email' => $email,
                'provider_uid' => $uid,
            ]);
        }

        $user->setAttribute('disabled', $disabled);
        $user->setAttribute('email_verified', $emailVerified);

        return $user;
    }
}
