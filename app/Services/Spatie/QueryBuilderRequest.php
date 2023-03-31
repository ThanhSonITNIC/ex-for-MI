<?php

namespace App\Services\Spatie;

use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilderRequest as QueryBuilderRequestBase;
use Illuminate\Http\Request;

class QueryBuilderRequest extends QueryBuilderRequestBase
{
    private static $subAppendsArrayValueDelimiter = ',';

    public static function fromRequest(Request $request): self
    {
        return static::createFrom($request, new self());
    }

    public function subAppends(): Collection
    {
        $subAppendParameterName = config('query-builder.parameters.sub_append');

        $subAppendParts = $this->getRequestData($subAppendParameterName);

        if (!is_array($subAppendParts)) {
            $subAppendParts = explode(static::getSubAppendsArrayValueDelimiter(), $subAppendParts);
        }

        return collect($subAppendParts)->filter();
    }

    public static function getSubAppendsArrayValueDelimiter(): string
    {
        return static::$subAppendsArrayValueDelimiter;
    }

    public function filltersBetween(): Collection
    {
        $filterParameterName = config('query-builder.parameters.filter_between');

        $filterParts = $this->getRequestData($filterParameterName, []);

        if (is_string($filterParts)) {
            return collect();
        }

        $filters = collect($filterParts);

        return $filters->map(function ($value) {
            return $this->getFilterValue($value);
        });
    }
}
