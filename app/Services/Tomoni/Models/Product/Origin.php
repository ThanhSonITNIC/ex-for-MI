<?php

namespace App\Services\Tomoni\Models\Product;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\ProductService;
use App\Services\Tomoni\Model;

class Origin extends Model
{
    protected static function apiResource(): string
    {
        return 'origins';
    }

    protected static function service(): MachineService
    {
        return new ProductService;
    }
}