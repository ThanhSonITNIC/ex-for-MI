<?php

namespace App\Validators;

use App\Contracts\ValidatorContract;
use Illuminate\Support\Str;

class ModelIdExists implements ValidatorContract
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        $modelName = $parameters[0];
        $model = resolve("\\App\Models\\" . $modelName);
        $columnName = $parameters[1] ?? $model->getKeyName();

        return (bool) $model->where(\DB::raw("BINARY `$columnName`"), $value)->first();
    }

    public function message($message, $attribute, $rule, $parameters): string
    {
        return 'The selected ' . str_replace(['_', '-'], ' ', Str::snake($attribute)) . ' is invalid.';
    }
}
