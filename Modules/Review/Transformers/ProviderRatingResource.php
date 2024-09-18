<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'image'                 => $this->getFirstMediaUrl('user'),
            'rating'                 => floor($this->reviews_avg_stars),
            'last_review'           => ReviewResource::make($this->reviews[0]),
        ];
    }
}
