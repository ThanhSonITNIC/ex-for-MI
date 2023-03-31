<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\ImplicitRule;

class Accessible implements ImplicitRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new User)->accessible()->find($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot be accessible.';
    }
}
