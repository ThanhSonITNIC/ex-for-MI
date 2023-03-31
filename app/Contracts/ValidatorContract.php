<?php

namespace App\Contracts;

interface ValidatorContract
{
    public function validate($attribute, $value, $parameters, $validator): bool;

    public function message($message, $attribute, $rule, $parameters): string;
}
