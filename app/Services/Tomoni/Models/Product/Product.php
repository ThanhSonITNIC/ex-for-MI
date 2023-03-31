<?php

namespace App\Services\Tomoni\Models\Product;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\ProductService;
use App\Services\Tomoni\Model;

class Product extends Model
{
    protected array $translatable = ['name'];

    protected static function apiResource(): string
    {
        return 'products';
    }

    protected static function service(): MachineService
    {
        return new ProductService;
    }
}
