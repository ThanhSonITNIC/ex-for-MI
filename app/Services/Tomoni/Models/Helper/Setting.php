<?php

namespace App\Services\Tomoni\Models\Helper;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;
use App\Services\Tomoni\Model;

class Setting extends Model
{
    protected static function apiResource(): string
    {
        return 'settings';
    }

    protected static function service(): MachineService
    {
        return new HelperService;
    }

    public static function defaultSetting(array $conditions)
    {
        return self::service()->get('default-setting', $conditions);
    }
}
