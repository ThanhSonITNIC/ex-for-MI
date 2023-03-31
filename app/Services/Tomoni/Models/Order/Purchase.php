<?php

namespace App\Services\Tomoni\Models\Order;

use App\Services\Tomoni\Machine\OrderService;
use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Model;

class Purchase extends Model
{
    protected static function apiResource(): string
    {
        return 'purchases';
    }

    protected static function service(): MachineService
    {
        return new OrderService;
    }
}
