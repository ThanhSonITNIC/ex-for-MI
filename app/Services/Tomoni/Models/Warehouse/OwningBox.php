<?php

namespace App\Services\Tomoni\Models\Warehouse;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\WarehouseService;
use App\Services\Tomoni\Model;

class OwningBox extends Model
{
    protected static function apiResource(): string
    {
        return 'owning-boxes';
    }

    protected static function service(): MachineService
    {
        return new WarehouseService;
    }
}
