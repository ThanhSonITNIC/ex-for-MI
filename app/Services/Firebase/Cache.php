<?php

namespace App\Services\Firebase;

use \App\Models\User;
use Carbon\Carbon;

class Cache
{
    const LIFETIME = 50; // minutes

    public static function user(string $token, User $user = null, Carbon $expire = null): ?User
    {
        if ($user) {
            $lifetime = $expire ? $expire->subMinutes(1) : now()->addMinutes(self::LIFETIME);
            \Cache::put($token, serialize($user), $lifetime);
            return $user;
        }

        $userSerialized = \Cache::get($token);
        return $userSerialized ? unserialize($userSerialized) : null;
    }
}