<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
{
    public static $isMasked = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        stiact:
        $isMasked = $this->customer->id != auth()->id();

        return [
            "id"                         => $this->id,
            "service_name"               => $this->service->profession->name,
            'customer_name'             =>  $this->customer->name,
            'customer_phone'             =>  $this->customer->phone,
            'customer_email'             =>  $this->customer->email,
            'customer_location'             =>  $this->customer->city->name,
            'customer_zip_code'         => $this->customer->zip_code,
            'is_approved'                => $this->is_approved,
            'total_credits'              => $this->total_credits,
            "status"                     => $this->status,
            'questions_data'             => $this->questions_data,
            'accepted_estimate'         => $this->acceptedEstimate? ProviderEstimateResource::make($this->acceptedEstimate): null,
            'can_send_estimate'          => $this->acceptedEstimate? $this->acceptedEstimate->provider_id != auth()->id(): true,
            'accepted'          => $this->acceptedEstimate? $this->acceptedEstimate->provider_id == auth()->id(): false,
            'estimates'                  => ProviderEstimateResource::collection($this->estimates),
            "created_at"                 => $this->created_at->diffForHumans(),
            "updated_at"                 => $this->updated_at->diffForHumans(),
        ];
    }

    public function maskEmail(string $email)
    {
        if (! static::$isMasked) {
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
        if (! static::$isMasked) {
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
