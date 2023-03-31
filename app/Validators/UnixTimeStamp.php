<?php

namespace App\Validators;

use App\Contracts\ValidatorContract;
use Illuminate\Support\Str;

class UnixTimeStamp implements ValidatorContract
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        return (filter_var($value, FILTER_VALIDATE_INT) !== false) && ($value >= 0 && $value <= 2147483647);
    }

    public function message($message, $attribute, $rule, $parameters): string
    {
        return 'The ' . str_replace(['_', '-'], ' ', Str::snake($attribute)) . ' may only integer and range from 0 to 2147483647.';
    }
}
