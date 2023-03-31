<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class ProductService extends MachineService
{
    public function serviceName(): string
    {
        return 'product';
    }
}
