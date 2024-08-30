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
            'match_count'     => $this->collection->count(),
            'service_count'     => $this->collection->groupBy('service_id')->count(),
            'locationcount'        =>   $this->collection->groupBy('city_id')->count(),
            'items' => LeadServiceRequestResource::collection($this->collection),
        ];
    }
}
