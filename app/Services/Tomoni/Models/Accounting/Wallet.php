<?php

namespace App\Services\Tomoni\Models\Accounting;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AccountingService;
use App\Services\Tomoni\Model;

class Wallet extends Model
{
    protected static function apiResource(): string
    {
        return 'wallets';
    }

    protected static function service(): MachineService
    {
        return new AccountingService;
    }
}
