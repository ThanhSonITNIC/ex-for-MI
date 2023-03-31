<?php

namespace App\Services\Tomoni\Models\Auth;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AuthService;
use App\Services\Tomoni\Model;

class User extends Model
{
    protected static function apiResource(): string
    {
        return 'users';
    }

    protected static function service(): MachineService
    {
        return new AuthService;
    }
}
