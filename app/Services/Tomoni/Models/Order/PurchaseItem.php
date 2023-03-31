<?php

namespace App\Services\Tomoni\Models\Order;

use App\Services\Tomoni\Machine\OrderService;
use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Model;

class PurchaseItem extends Model
{
    protected static function apiResource(): string
    {
        return 'purchases/items';
    }

    protected static function meilisearchResource(): string
    {
        return 'purchase_items';
    }

    protected static function service(): MachineService
    {
        return new OrderService;
    }
}
