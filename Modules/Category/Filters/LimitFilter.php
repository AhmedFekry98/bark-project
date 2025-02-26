<?php

namespace Modules\Category\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class LimitFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property)
    {
        $query->limit($value);
    }
}
