<?php

namespace App\Services\Tomoni\Models\Helper;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;
use App\Services\Tomoni\Model;

class Price extends Model
{
    protected static function apiResource(): string
    {
        return 'prices';
    }

    protected static function service(): MachineService
    {
        return new HelperService;
    }

    public static function amountWithConditions(array $query = [])
    {
        return static::service()->get('amount-with-conditions', $query);
    }
}
