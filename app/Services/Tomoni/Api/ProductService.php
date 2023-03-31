<?php

namespace App\Services\Tomoni\Api;

use App\Services\Tomoni\ApiService;

class ProductService extends ApiService
{
    public function serviceName(): string
    {
        return 'product';
    }
}
