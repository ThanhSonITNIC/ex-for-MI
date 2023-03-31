<?php

namespace App\Services\Cache;

class Tomoni extends Base
{
    const LIFETIME = 21600; // 21600 minutes ~ 15 days

    public static function tags()
    {
        return ['tomoni'];
    }
}
