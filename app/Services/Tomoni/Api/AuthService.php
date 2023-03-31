<?php

namespace App\Services\Tomoni\Api;

use App\Services\Tomoni\ApiService;

class AuthService extends ApiService
{
    public function serviceName(): string
    {
        return 'auth';
    }
}
