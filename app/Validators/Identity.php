<?php

namespace App\Validators;

use App\Contracts\ValidatorContract;
use Illuminate\Support\Str;

class Identity implements ValidatorContract
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        if (!is_string($value) || !Str::isAscii($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\pN\_-]+$/u', $value) > 0;
    }

    public function message($message, $attribute, $rule, $parameters): string
    {
        return 'The ' . str_replace(['_', '-'], ' ', Str::snake($attribute)) . ' may only contain letters, numbers, dashes and underscores.';
    }
}
