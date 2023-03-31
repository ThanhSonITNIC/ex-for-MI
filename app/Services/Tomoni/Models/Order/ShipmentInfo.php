<?php

namespace App\Services\Tomoni\Models\Order;

use App\Services\Tomoni\Machine\OrderService;
use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Model;

class ShipmentInfo extends Model
{
    public static function getKeyName(): string
    {
        return 'id_order';
    }

    protected static function meilisearchResource(): string
    {
        return 'shipment_infos';
    }

    protected static function apiResource(): string
    {
        return 'orders/shipment-infos';
    }

    protected static function service(): MachineService
    {
        return new OrderService;
    }
}
