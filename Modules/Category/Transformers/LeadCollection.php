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
            'count'     => $this->collection->count(),
            'service_count'     => 5,
            'match_count'     => 1,
            'location'        =>   fake()->city(),
            'items' => LeadServiceRequestResource::collection($this->collection),
        ];
    }
}
