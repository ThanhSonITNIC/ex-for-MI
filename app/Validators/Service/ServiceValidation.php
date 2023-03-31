<?php

namespace App\Validators\Service;

use App\Contracts\ValidatorContract;
use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Model;
use Illuminate\Support\Str;

abstract class ServiceValidation extends Model  implements ValidatorContract
{
    protected static $resource;
    protected static $service;

    protected static function apiResource(): string
    {
        return static::$resource;
    }

    protected static function service(): MachineService
    {
        return resolve('App\Services\Tomoni\Machine\\' . Str::ucfirst(static::$service) . 'Service'::class);
    }

    public function validate($attribute, $value, $parameters, $validator): bool
    {
        $service = $parameters[0];
        $resource = $parameters[1];

        static::$service = $service;
        static::$resource = $resource;

        return $this->exec($attribute, $value, $resource);
    }

    abstract protected function exec($attribute, $value, $resource): bool;
}
