<?php

namespace App\Services\Spatie\SubAppend\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\Exceptions\InvalidQuery;

class InvalidSubAppendQuery extends InvalidQuery
{
    /** @var \Illuminate\Support\Collection */
    public $subAppendsNotAllowed;

    /** @var \Illuminate\Support\Collection */
    public $allowedSubAppends;

    public function __construct(Collection $subAppendsNotAllowed, Collection $allowedSubAppends)
    {
        $this->subAppendsNotAllowed = $subAppendsNotAllowed;
        $this->allowedSubAppends = $allowedSubAppends;

        $subAppendsNotAllowed = $subAppendsNotAllowed->implode(', ');
        $allowedSubAppends = $allowedSubAppends->implode(', ');
        $message = "Requested sub_append(s) `{$subAppendsNotAllowed}` are not allowed. Allowed sub_append(s) are `{$allowedSubAppends}`.";

        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }

    public static function subAppendsNotAllowed(Collection $subAppendsNotAllowed, Collection $allowedSubAppends)
    {
        return new static(...func_get_args());
    }
}
