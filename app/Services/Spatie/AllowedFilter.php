<?php

namespace App\Services\Spatie;

use App\Services\Spatie\FilterBetween\FiltersBetween;
use Spatie\QueryBuilder\AllowedFilter as AllowedFilterBase;

class AllowedFilter extends AllowedFilterBase
{
    public static function between(string $name, ?string $internalName = null, bool $openInterval = true): self
    {
        return new static($name, new FiltersBetween($openInterval), $internalName);
    }

    public function filterBetween(QueryBuilder $query, array $values)
    {
        ($this->filterClass)($query->getEloquentBuilder(), $values, $this->internalName);
    }
}
