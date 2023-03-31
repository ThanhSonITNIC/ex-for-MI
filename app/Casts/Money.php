<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Money implements CastsAttributes
{
    // The default optional number of decimal digits to round to.
    CONST DEFAULT_PRECISION = 0;
    protected int $precision;

    public function __construct(int|null $precision = null)
    {
        $this->precision = $precision ?: self::DEFAULT_PRECISION;
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return round($model->fromFloat($value), $this->precision);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        $value = $model->fromFloat($value);
        if ($value > -1 && $value < 1) {
            return 0;
        } else {
            return round($value, $this->precision);
        }
    }
}
