<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\ServiceRequestResource;
use Modules\Category\Transformers\ServiceResource;

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
                "name"              => $this->name,
                'phone'             => $this->phone,
                'email'             => $this->email,
                'image'             => $this->getFirstMediaUrl('user'),
                // 'verified'          =>  $this->is_verified,
                'role'              => $role,
                // 'abilities'         => $this->abilities,
            ],

            $role == 'customer' ? [
                'serviceRequestsSent'   => ServiceRequestResource::collection($this->serviceRequestsSent),
            ] : [],

            $role == 'provider' ? [
                'company_name'  => $this->company_name,
                'company_website'  => $this->company_website,
                'company_size'       => $this->company_size,
                'sales'              => $this->sales,
                'social'             => $this->social,
                'professions'           => ProfessionResource::collection($this->professions),
                'serviceRequests'   => ServiceRequestResource::collection($this->serviceRequests),
            ]
                : [],

            [
                'created_at'        => $this->created_at,
                'updated_at'         => $this->updated_at
            ]
        );
    }
}
