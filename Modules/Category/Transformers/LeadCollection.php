<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LeadCollection extends ResourceCollection
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
            'service_count'     => $this->collection->count(),
            'match_count'     => 1,
            'locationcount'        =>   5,
            'items' => LeadServiceRequestResource::collection($this->collection),
        ];
    }
}
