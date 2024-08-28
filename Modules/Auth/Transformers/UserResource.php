<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\ServiceRequestResource;
use Modules\Category\Transformers\ServiceResource;

class UserResource extends JsonResource
{

    /**
     * Switch to masking the contact information
     *
     * @var bool $isMasked
     */
    public static $isMasked = true;

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
                'phone'             => $this->maskPhone($this->phone),
                'email'             => $this->maskEmail($this->email),
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
                'service'           => ServiceResource::make($this->service),
                'service_data'      => $this->service_data,
                'serviceRequests'   => ServiceRequestResource::collection($this->serviceRequests),
            ]
                : [],

            [
                'created_at'        => $this->created_at,
                'updated_at'         => $this->updated_at
            ]
        );
    }

    public function maskEmail(string $email)
    {
        if (! static::$isMasked || $this->id == auth()->id()) {
            return $email;
        }

        list($username, $domain) = explode('@', $email);
        $usernameLength = strlen($username);

        if ($usernameLength <= 2) {
            return str_repeat('*', $usernameLength) . '@' . $domain;
        }

        $visiblePart = substr($username, 0, 2); // أول 2 أحرف تكون مرئية
        $maskedPart = str_repeat('*', $usernameLength - 2); // البقية تكون على شكل نجوم
        return $visiblePart . $maskedPart . '@' . $domain;
    }

    public function maskPhone(string $phone)
    {
        if (! static::$isMasked || $this->id == auth()->id()) {
            return $phone;
        }

        $phoneLength = strlen($phone);

        if ($phoneLength <= 3) {
            return str_repeat('*', $phoneLength);
        }

        $visiblePart = substr($phone, -3);
        $maskedPart  = str_repeat('*', $phoneLength - 3);
        return $maskedPart . $visiblePart;
    }
}
