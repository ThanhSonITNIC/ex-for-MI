<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class OrderService extends MachineService
{
    public function serviceName(): string
    {
        return 'order';
    }
}
