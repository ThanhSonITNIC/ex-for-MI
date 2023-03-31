<?php

namespace App\Services\Tomoni\Relationships\Internal;

use App\Services\Tomoni\Relationships\Internal\Jobs\UpdateCacheField;
use Illuminate\Database\Eloquent\Collection;

class CacheField
{
    public static function update(Collection $models): void
    {
        UpdateCacheField::dispatch($models);
    }
}
