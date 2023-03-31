<?php

namespace App\Services\Tomoni;

use App\Services\Tomoni\Exceptions\CommunicationException;
use App\Services\Meilisearch\Builder;

abstract class Model
{
    protected static $pagination = true;

    protected array $translatable = [];

    abstract protected static function apiResource(): string;

    protected function getTranslatable()
    {
        return $this->translatable;
    }

    protected static function meilisearchResource(): string
    {
        return str_replace('-', '_', static::apiResource());
    }

    abstract protected static function service(): ApiService;

    public static function getKeyName(): string
    {
        return 'id';
    }

    protected static function indexKey(): string
    {
        return static::service()->serviceName() . '-' . static::meilisearchResource();
    }

    protected static function builder()
    {
        return new Builder(static::indexKey(), (new static)->getTranslatable());
    }

    public static function getFromOrigin(array $query = [])
    {
        $data = static::service()->get(static::apiResource(), $query);

        if (static::$pagination) {
            return $data->data;
        }

        return $data;
    }

    public static function create(array $data)
    {
        return static::service()->post(static::apiResource(), $data);
    }

    public static function findFromOrigin(string|int $id, array $query = [])
    {
        try {
            return static::service()->get(static::apiResource() . '/' . $id, $query);
        } catch (CommunicationException $ex) {
            return null;
        };
    }

    public static function update(array $data, string|int $id)
    {
        return static::service()->put(static::apiResource() . '/' . $id, $data);
    }

    public static function delete(string|int $id, array $query = [])
    {
        return static::service()->delete(static::apiResource() . '/' . $id, $query);
    }

    public static function find(string|int $id)
    {
        $model = static::builder()->where(static::getKeyName(), '=', $id)->first();
        return $model?->{static::getKeyName()} == $id ? $model : static::findFromOrigin($id);
    }

    public static function get()
    {
        return static::builder()->get();
    }

    public static function where(string $key, string $operator, $value)
    {
        return static::builder()->where($key, $operator, $value);
    }

    public static function whereIn(string $key, array $value)
    {
        return static::builder()->whereIn($key, $value);
    }

    public static function orWhere(array $wheres)
    {
        return static::builder()->orWhere($wheres);
    }
}
