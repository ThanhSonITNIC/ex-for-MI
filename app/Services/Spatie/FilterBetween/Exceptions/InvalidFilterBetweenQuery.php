<?php

namespace App\Services\Spatie\FilterBetween\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\Exceptions\InvalidQuery;

class InvalidFilterBetweenQuery extends InvalidQuery
{
    /** @var \Illuminate\Support\Collection */
    public $unknownFiltersBetween;

    /** @var \Illuminate\Support\Collection */
    public $allowedFiltersBetween;

    public function __construct(Collection $unknownFiltersBetween, Collection $allowedFiltersBetween)
    {
        $this->unknownFiltersBetween = $unknownFiltersBetween;
        $this->allowedFiltersBetween = $allowedFiltersBetween;

        $unknownFiltersBetween = $this->unknownFiltersBetween->implode(', ');
        $allowedFiltersBetween = $this->allowedFiltersBetween->implode(', ');
        $message = "Requested filter_between(s) `{$unknownFiltersBetween}` are not allowed. Allowed filter_between(s) are `{$allowedFiltersBetween}`.";

        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }

    public static function filtersBetweenNotAllowed(Collection $unknownFiltersBetween, Collection $allowedFiltersBetween)
    {
        return new static(...func_get_args());
    }
}
