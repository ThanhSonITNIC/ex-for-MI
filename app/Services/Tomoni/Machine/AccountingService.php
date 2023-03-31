<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class AccountingService extends MachineService
{
    public function serviceName(): string
    {
        return 'accounting';
    }
}
