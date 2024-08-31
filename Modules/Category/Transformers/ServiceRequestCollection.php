<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceRequestCollection extends ResourceCollection
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
            'count' => $this->collection->count(),
            'updated_at' => $this->collection->last()?->updated_at->diffForHumans(),
            'items' => ServiceRequestResource::collection($this->collection)
        ];
    }
}
