<?php

namespace App\Services\Tomoni\Models\Accounting;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AccountingService;
use App\Services\Tomoni\Model;

class Currency extends Model
{
    const VND = 'VND';
    const JPY = 'JPY';
    const USD = 'USD';

    protected static function apiResource(): string
    {
        return 'currencies';
    }

    protected static function service(): MachineService
    {
        return new AccountingService;
    }
}
