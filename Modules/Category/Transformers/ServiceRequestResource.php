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
            "id"                => $this->id,
            "service_id"        => $this->service_id,
            "hired_id"          => $this->hired_id,
            "questions_data"    => $this->questions_data,
            "created_at"        => $this->created_at,
        ];
    }
}
