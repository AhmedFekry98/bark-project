<?php

namespace Modules\Badge\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
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
            'id'                => $this->id,
            'icon'              => $this->getFirstMediaUrl('icon'),
            'label'             => $this->label,
            'assignedTo'        => $this->providers_count,
        ];
    }
}
