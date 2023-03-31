<?php

namespace App\Services\Spatie\FilterBetween\Traits;

use App\Services\Spatie\AllowedFilter;
use App\Services\Spatie\FilterBetween\Exceptions\InvalidFilterBetweenQuery;

trait FiltersBetweenQuery
{
    /** @var \Illuminate\Support\Collection */
    protected $allowedFiltersBetween;

    public function allowedFiltersBetween($filters): self
    {
        $filters = is_array($filters) ? $filters : func_get_args();

        $this->allowedFiltersBetween = collect($filters)->map(function ($filter) {
            if ($filter instanceof AllowedFilter) {
                return $filter;
            }

            return AllowedFilter::between($filter);
        });

        $this->ensureAllFiltersBetweenExist();

        $this->addFiltersBetweenToQuery();

        return $this;
    }

    protected function addFiltersBetweenToQuery()
    {
        $this->allowedFiltersBetween->each(function (AllowedFilter $filter) {

            if ($this->isFilterBetweenRequested($filter)) {
                $values = $this->request->filltersBetween()->get($filter->getName());
                $filter->filterBetween($this, $values);

                return;
            }
        });
    }

    protected function isFilterBetweenRequested(AllowedFilter $allowedFilter): bool
    {
        return $this->request->filltersBetween()->has($allowedFilter->getName());
    }

    protected function ensureAllFiltersBetweenExist()
    {
        if (config('query-builder.disable_invalid_filter_query_exception')) {
            return;
        }

        $filterNames = $this->request->filltersBetween()->keys();

        $allowedFilterNames = $this->allowedFiltersBetween->map(function (AllowedFilter $allowedFilter) {
            return $allowedFilter->getName();
        });

        $diff = $filterNames->diff($allowedFilterNames);

        if ($diff->count()) {
            throw InvalidFilterBetweenQuery::filtersBetweenNotAllowed($diff, $allowedFilterNames);
        }
    }
}
