<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'id'      => $this->conversation->id,
            'user_id' => $this->user_id,
            'name'    => $this->name,
            'samary'  => $this->with_samary? [
                'is_sent'  => auth()->id() == $this->samary->user_id,
                'content'  => $this->samary->content,
            ]: null,
            'messages'    => $this->with_messages ? MessageResource::collection($this->conversation->messages) : []
        ];
    }
}
