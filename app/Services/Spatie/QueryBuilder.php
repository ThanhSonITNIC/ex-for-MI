<?php

namespace App\Services\Spatie;

use Spatie\QueryBuilder\QueryBuilder as QueryBuilderBase;
use App\Services\Spatie\SubAppend\Traits\SubAppendsQuery;
use App\Services\Spatie\FilterBetween\Traits\FiltersBetweenQuery;
use Illuminate\Http\Request;

class QueryBuilder extends QueryBuilderBase
{
    use SubAppendsQuery, FiltersBetweenQuery;

    protected function initializeRequest(?Request $request = null): self
    {
        $this->request = $request
            ? QueryBuilderRequest::fromRequest($request)
            : app(QueryBuilderRequest::class);

        return $this;
    }
}
