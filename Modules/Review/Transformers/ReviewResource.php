<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'id'            => $this->id,
            'service_name'  => $this->request->service->profession->name,
            'reviewer'      => [
                'id'    => $this->reviewer->id,
                'name'  => $this->reviewer->name
            ],
            'comment'       => $this->comment,
            'stars'         => $this->stars,
            'keeped_at'     => $this->created_at->diffForHumans(),
        ];
    }
}
