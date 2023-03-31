<?php

namespace App\Services\Tomoni\Models\Warehouse;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\WarehouseService;
use App\Services\Tomoni\Model;

class ShipmentMethod extends Model
{
    const AIR = 'air';
    const SEA = 'sea';

    protected static function apiResource(): string
    {
        return 'shipment-methods';
    }

    protected static function service(): MachineService
    {
        return new WarehouseService;
    }
}
