<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class MustBeNoRelations implements Rule
{
    protected array $relations_exist = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected Model $model, protected array $relations = [])
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->relations as $relation) {
            if ($this->model->{$relation}()->exists()) {
                $this->relations_exist[] = $relation;
            }
        }

        return !count($this->relations_exist);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'There must be no relations ' . implode(", ", $this->relations_exist);
    }
}
