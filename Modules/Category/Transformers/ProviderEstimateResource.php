<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Review\Transformers\ReviewResource;

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
            'provider_email'    => $this->provider->email,
            'provider_phone'    => $this->provider->phone,
            "provider_image"    => $this->provider->getFirstMediaUrl('user'),
            'rating'            => floor($this->provider->reviews()->avg('stars')),
            "price"             => $this->price,
            "estimated_time"    => $this->estimated_time,
            "addational_notes"  => $this->addational_notes,
            "status"            =>  $this->status,
            'review'            =>  ReviewResource::make($this->provider->reviews()->whereReviewerId(auth()->id())->first()),
            "created_at"         => $this->created_at->diffForHumans(),
        ];
    }
}
