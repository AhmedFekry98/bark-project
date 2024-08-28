<?php

namespace Modules\Category\Filters;

use Illuminate\Console\View\Components\BulletList;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property)
    {
        $query->whereHas('profession', function (Builder $q) use ($value) {
            $q->where('name', 'like', "%$value%");
        })
            ->orWhereHas('category', function (Builder $q) use ($value) {
                $q->where('name', 'like', "%$value%");
            });
    }
}
