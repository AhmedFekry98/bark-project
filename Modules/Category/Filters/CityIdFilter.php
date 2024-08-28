<?php

namespace Modules\Category\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CityIdFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property)
    {
        // 1 query services where has provider at spcified city
        //  and has service requests with spcified city.
    }
}
