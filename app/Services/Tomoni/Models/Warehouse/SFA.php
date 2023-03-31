<?php

namespace App\Services\Tomoni\Models\Warehouse;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\WarehouseService;
use App\Services\Tomoni\Model;

class SFA extends Model
{
    protected static function apiResource(): string
    {
        return 'sfas';
    }

    protected static function meilisearchResource(): string
    {
        return 's_f_a_s';
    }

    protected static function service(): MachineService
    {
        return new WarehouseService;
    }

    public static function findByTrackingId(string $tracking_id)
    {
        $sfas_by_tracking_id = static::getFromOrigin(['filter[tracking_id]' => $tracking_id]);
        return collect($sfas_by_tracking_id)->first();
    }
}
