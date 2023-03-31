<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class WarehouseService extends MachineService
{
    public function serviceName(): string
    {
        return 'warehouse';
    }
}
