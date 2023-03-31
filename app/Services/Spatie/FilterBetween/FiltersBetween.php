<?php

namespace App\Services\Spatie\FilterBetween;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersBetween implements Filter
{
    protected $openInterval = true;
    const INFINITY_SYMBOL = 'infinity';

    public function __construct(bool $openInterval = true)
    {
        $this->openInterval = $openInterval;
    }

    public function __invoke(Builder $query, $values, string $property)
    {
        if (is_array($values) && count($values) == 2) {

            if (!strpos($property, '.')) {
                return $this->whereBetween($query, $property, $values[0], $values[1]);
            }

            $conditionsToArray = explode('.', $property);
            $relations = implode('.', array_splice($conditionsToArray, 0, -1));
            $field = $conditionsToArray[array_key_last($conditionsToArray)];

            return $query->whereHas($relations, function ($query) use ($field, $values) {
                return $this->whereBetween($query, $field, $values[0], $values[1]);
            });
        }
    }

    public function whereBetween(Builder $query, string $field, $startValue, $endValue)
    {
        if (!$this->isInfinitySymbol($startValue)) {
            $query->where($query->qualifyColumn($field), $this->operatorForWhereGreater(), (float) $startValue);
        }

        if (!$this->isInfinitySymbol($endValue)) {
            $query->where($query->qualifyColumn($field), $this->operatorForWhereLess(), (float) $endValue);
        }

        return $query;
    }

    public function operatorForWhereLess(): string
    {
        return $this->openInterval ? '<' : '<=';
    }

    public function operatorForWhereGreater(): string
    {
        return $this->openInterval ? '>' : '>=';
    }

    public function isInfinitySymbol($value): bool
    {
        return (string) $value == static::INFINITY_SYMBOL;
    }
}
