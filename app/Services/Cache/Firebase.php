<?php

namespace App\Services\Cache;

class Firebase extends Base
{
    const LIFETIME = 50; // minutes

    public static function tags()
    {
        return ['firebase'];
    }
}
