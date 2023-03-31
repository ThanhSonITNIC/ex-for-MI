<?php

namespace App\Services\Tomoni\Machine;

use App\Services\Tomoni\MachineService;

class TomoniService extends MachineService
{
    public function serviceName(): string
    {
        return 'auth';
    }

    public function getToken()
    {
        return request()->bearerToken() ?: null;
    }
}
