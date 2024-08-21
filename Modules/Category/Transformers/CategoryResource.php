<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $condationalData = [];

        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'image'     => $this->getFirstMediaUrl('image'),
            'questions' => CategoryQuestionResource::collection($this->categoryQuestions),
        ];
    }
}
