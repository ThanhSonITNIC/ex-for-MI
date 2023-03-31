<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache as CacheService;

class Base
{
    const LIFETIME = null; // minutes

    public static function tags()
    {
        return [request()->ip()];
    }

    public static function taggedCache()
    {
        return CacheService::tags(static::tags());
    }

    public static function getOrPut(string $key, $value = null)
    {
        if (!static::has($key)) {
            static::put($key, $value);
        }

        return $value;
    }

    public static function has(string $key)
    {
        return static::taggedCache()->has($key);
    }

    public static function get(string $key)
    {
        return static::taggedCache()->get($key);
    }

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int|null  $lifetime - minute unit
     * @return bool
     */
    public static function put(string $key, $value, int $lifetime = null)
    {
        if ($lifetime) {
            return static::taggedCache()->put($key, $value, now()->addMinutes($lifetime));
        }

        if (static::LIFETIME) {
            return static::taggedCache()->put($key, $value, now()->addMinutes(static::LIFETIME));
        }

        return static::taggedCache()->put($key, $value);
    }

    public static function flush()
    {
        return static::taggedCache()->flush();
    }
}
