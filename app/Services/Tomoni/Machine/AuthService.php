<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class AuthService extends MachineService
{
    public function serviceName(): string
    {
        return 'auth';
    }
}
