<?php

namespace App\Services\Tomoni\Models\Helper;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;
use App\Services\Tomoni\Model;

class Ward extends Model
{
    protected static function apiResource(): string
    {
        return 'wards';
    }

    protected static function service(): MachineService
    {
        return new HelperService;
    }
}
