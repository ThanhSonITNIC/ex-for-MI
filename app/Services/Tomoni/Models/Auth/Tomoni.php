<?php

namespace App\Services\Tomoni\Models\Auth;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\TomoniService;

class Tomoni extends Me
{
    protected static function service(): MachineService
    {
        return new TomoniService;
    }
}
