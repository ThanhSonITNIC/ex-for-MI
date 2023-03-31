<?php

namespace App\Services\Meilisearch;

use Laravel\Scout\ModelObserver;

class ModelsSearchable
{
    public static function enableSyncing()
    {
        foreach (self::$register as $model_class) {
            ModelObserver::enableSyncingFor($model_class);
        }
    }

    public static function disableSyncing()
    {
        foreach (self::$register as $model_class) {
            ModelObserver::disableSyncingFor($model_class);
        }
    }

    /**
     * Register models to search
     */
    public static $register = [
        // \App\Models\Example::class,
    ];
}
