<?php

namespace App\Services\Tomoni;

abstract class MachineService extends ApiService
{
    protected $prefix = 'machine';

    public function getToken()
    {
        return env('TOMONI_TOKEN') ?: null;
    }

    public function getHeaders()
    {
        return [
            'Accept'     => 'application/json',
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Accept-Language' => app()->getLocale(),
        ];
    }
}
