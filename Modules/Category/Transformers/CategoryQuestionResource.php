<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryQuestionResource extends JsonResource
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
            'question_text'     => $this->question_text,
            'question_note'     => $this->question_note,
            'type'              => $this->type,
            'details'           => $this->details,
        ];
    }
}
