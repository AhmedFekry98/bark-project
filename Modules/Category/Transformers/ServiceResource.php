<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'id'               => $this->id,
            'category_id'   => $this->category_id,
            "category_name" => $this->category->name,
            'profession_id' => $this->profession_id,
            'name'  => $this->profession->name,
            'image'            => $this->getFirstMediaUrl('image'),
            'questions'        => QuestionResource::collection($this->questions),
        ];
    }
}
