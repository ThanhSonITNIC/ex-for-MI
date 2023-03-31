<?php

namespace App\Traits;

use App\Services\Meilisearch\ModelsSearchable;
use App\Services\Tomoni\Relationships\Internal\CacheField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait HasInternalRelationships
{
    // protected $cacheFields = [];

    public function getCacheFields(): array
    {
        return $this->cacheFields;
    }

    public function checkHasCacheField(): bool
    {
        return count($this->getCacheFields());
    }

    public function cacheable(bool $using_queue = false)
    {
        static::makeCacheable($this->newCollection([$this]), $using_queue);
    }

    /**
     *
     * mass update
     */
    public static function syncCacheField(Collection $models)
    {
        ModelsSearchable::disableSyncing();
        $models->each(function ($model) {
            $model->refreshCacheFields();
            $model->updateQuietly($model->getDataCacheFields());
        });
        ModelsSearchable::enableSyncing();
    }

    public function getDataCacheFields(): array
    {
        return collect($this->getCacheFields())->flatMap(function ($field) {
            return [$field => $this->attributes[$field]];
        })->toArray();
    }

    protected bool $cacheFieldsRefreshed = false;

    public function refreshCacheFields(bool $force = false)
    {
        if ($this->cacheFieldsRefreshed && !$force) {
            return;
        }

        $cache_fields = $this->getCacheFields();
        foreach ($cache_fields as $field_name) {
            $target_method = 'set' . Str::camel($field_name) . 'Attribute';
            $this->$target_method();
        }

        $this->cacheFieldsRefreshed = true;
    }

    public static function makeCacheableWithoutQueue($models)
    {
        return static::syncCacheField($models);
    }

    public static function makeCacheableUsingQueue($models)
    {
        return CacheField::update($models);
    }

    public static function makeAllCacheable(bool $using_queue = false)
    {
        if (Schema::hasColumn((new static)->getTable(), 'updated_at') && env('V1_END_DATE', null)) {
            static::where('updated_at', '>', env('V1_END_DATE'))->chunk(500, function ($models) use ($using_queue) {
                return static::makeCacheable($models, $using_queue);
            });
        } else {
            static::chunk(500, function ($models) use ($using_queue) {
                return static::makeCacheable($models, $using_queue);
            });
        }
    }

    public static function makeCacheable($models, bool $using_queue = false)
    {
        $self = new static;
        if ($models->isEmpty() || !$self->checkHasCacheField()) {
            return;
        }
        $self->validateHasColumns($self->getCacheFields());
        $self->validateExistCacheFieldMethod();

        return $using_queue ? static::makeCacheableUsingQueue($models) : static::makeCacheableWithoutQueue($models);
    }

    public function validateExistCacheFieldMethod(): void
    {
        $methods_not_exist = collect();
        foreach ($this->getCacheFields() as $field_name) {
            $target_method = 'set' . Str::camel($field_name) . 'Attribute';
            if (!method_exists($this, $target_method)) {
                $methods_not_exist->push($target_method);
            }
        }

        if ($methods_not_exist->count()) {
            throw new \App\Exceptions\MethodsDoesNotExist('Methods ' . $methods_not_exist->implode(', ') . ' do not exist in model ' . get_class($this));
        }
    }
}
