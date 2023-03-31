<?php

namespace App\Services\Tomoni\Models\Auth;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AuthService;
use App\Services\Tomoni\Model;

class Address extends Model
{
    protected static function apiResource(): string
    {
        return 'addresses';
    }

    protected static function service(): MachineService
    {
        return new AuthService;
    }
}
