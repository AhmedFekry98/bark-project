<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderEstimateResource extends JsonResource
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
            "id"                => $this->id,
            "provider_id"     => $this->provider->id,
            "provider_name"     => $this->provider->name,
            "provider_image"    => $this->getFirstMediaUrl('user'),
            "price"             => $this->price,
            "estimated_time"    => $this->estimated_time,
            "addational_notes"  => $this->addational_notes,
            "created_at"         => $this->created_at->diffForHumans(),
        ];
    }
}
