<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
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
            "id"                         => $this->id,
            "service_name"                => $this->service?->name,
            "hired_provider_name"         => $this->provider?->name,
            'customer_name'          => $this->customer?->name,
            "questions_data"             => $this->questions_data,
            "created_at"                => $this->created_at,
        ];
    }
}
