<?php

namespace App\QueryBuilders;

use App\Services\Spatie\AllowedFilter;
use App\Services\Spatie\QueryBuilder;

class APIQueryBuilder
{
    public static function builder($builder)
    {
        return QueryBuilder::for($builder)
            ->allowedFilters(array_merge(static::allowedFilters(), static::defaultAllowedFilters()))
            ->allowedFiltersBetween(static::allowedFiltersBetween())
            ->allowedSorts(static::allowedSorts())
            ->allowedIncludes(static::allowedIncludes())
            ->allowedAppends(static::allowedAppends())
            ->allowedSubAppends(static::allowedSubAppends());
    }

    protected static function defaultAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('own'),
        ];
    }

    protected static function allowedFilters(): array
    {
        return [];
    }

    protected static function allowedFiltersBetween(): array
    {
        return [];
    }

    protected static function allowedSorts(): array
    {
        return [];
    }

    protected static function allowedIncludes(): array
    {
        return [];
    }

    protected static function allowedAppends(): array
    {
        return [];
    }

    protected static function allowedSubAppends(): array
    {
        return [];
    }
}
