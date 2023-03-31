<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class HelperService extends MachineService
{
    public function serviceName(): string
    {
        return 'helper';
    }
}
