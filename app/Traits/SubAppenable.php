<?php

namespace App\Traits;

trait SubAppenable
{
    public static $defaultAppends = [];

    public static function boot()
    {
        static::retrieved(function ($model) {
            $model->append(static::$defaultAppends);
        });

        parent::boot();
    }

    public function setDefaultAppends($attributes)
    {
        static::$defaultAppends = array_unique(
            array_merge(static::$defaultAppends, is_string($attributes) ? func_get_args() : $attributes)
        );
    }
}
