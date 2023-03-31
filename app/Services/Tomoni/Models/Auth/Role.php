<?php

namespace App\Services\Tomoni\Models\Auth;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AuthService;
use App\Services\Tomoni\Model;

class Role extends Model
{
    protected static function apiResource(): string
    {
        return 'roles';
    }

    protected static function service(): MachineService
    {
        return new AuthService;
    }
}
