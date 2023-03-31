<?php

namespace App\Services\Tomoni\Api;

use App\Services\Tomoni\ApiService;

class OrderService extends ApiService
{
    public function serviceName(): string
    {
        return 'order';
    }
}
