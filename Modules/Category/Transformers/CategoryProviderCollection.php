<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryProviderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "count"     => $this->collection->count(),
            "items"     => CategoryProviderResource::collection($this->collection),
        ];
    }
}
