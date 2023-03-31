<?php

namespace App\Validators\Service;

use Illuminate\Support\Str;

class Exists extends ServiceValidation
{
    protected function exec($attribute, $value, $resource): bool
    {
        try {
            return (bool) (self::find($value));
        } catch (\App\Services\Tomoni\Exceptions\CommunicationException $ex) {
            return false;
        }
    }

    public function message($message, $attribute, $rule, $parameters): string
    {
        return 'The selected ' . str_replace(['_', '-'], ' ', Str::snake($attribute)) . ' is invalid.';
    }
}
