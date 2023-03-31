<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class ValidationServiceProvider extends ServiceProvider
{
    protected $validators = [
        'model_id.exists' => 'ModelIdExists',
        'service.exists' => 'Service\Exists',
        'identity' => 'Identity',
        'unix_time_stamp' => 'UnixTimeStamp',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidators();
    }

    public function registerValidators()
    {
        foreach ($this->validators as $rule => $path) {
            $validator = '\App\Validators\\' . $path;
            Validator::extend($rule, $validator . '@validate');
            Validator::replacer($rule, $validator . '@message');
        }
    }
}
