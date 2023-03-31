<?php

namespace App\Services\Tomoni\Models\Auth;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AuthService;
use App\Services\Tomoni\Model;

class Organization extends Model
{
    const TOMONI_VN = 'tomoni-vn';
    const TOMONI_JP = 'tomoni-jp';
    const SAIKO_EXPRESS = 'se';

    protected static function apiResource(): string
    {
        return 'organizations';
    }

    protected static function service(): MachineService
    {
        return new AuthService;
    }
}
