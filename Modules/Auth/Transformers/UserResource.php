<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $role = $this->role;

        return array_merge(
            // start of object
            [
                'first_name'        => $this->first_name,
                'last_name'         => $this->last_name,
                'full_name'      => "{$this->first_name} {$this->last_name}",
                'phone_number'      => $this->phone_number,
                'email'             => $this->email,
                'image'             => $this->getFirstMediaUrl('user'),
                'verified'          =>  $this->is_verified,
                'role'              => $role,
                'abilities'         => $this->abilities
            ],

            $role == 'restaurant' ? [
                'restaurant_name'  => $this->restaurant->restaurant_name,
                'restaurant_image' => $this->restaurant->getFirstMediaUrl('restaurant-caver'),
                'custom_domain',
                'open_time'        => $this->restaurant->open_time->format('h:i A'),
                'close_time'       => $this->restaurant->close_time->format('h:i A'),
            ] : [],

            $this->extra ?? [],

            $this->restaurant->socialmedia_links ?? [],

            [
                'created_at'        => $this->created_at,
                'updated_at'         => $this->updated_at
            ]
        );
    }
}
