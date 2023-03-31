<?php

namespace App\Services\Tomoni\Api;

use App\Services\Tomoni\ApiService;

class AccountingService extends ApiService
{
    public function serviceName(): string
    {
        return 'accounting';
    }
}
