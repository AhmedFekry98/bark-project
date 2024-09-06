<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LeadServiceRequestResource extends JsonResource
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
        return [
            "id"                         => $this->id,
            "service_name"                => $this->service->profession->name,
            'customer_image'              => $this->customer->getFirstMediaUrl('user'),
            'customer_name'              => $this->customer->name,
            'customer_email'            => $this->maskEmail($this->customer->email),
            'customer_phone'            => $this->maskPhone($this->customer->phone),
            'customer_location'         => $this->city->name,
            'customer_zip_code'         => $this->customer->zip_code,
            "questions_data"             => $this->questions_data,
            'isHired'                   =>   $this->_hired_id? true: false,
            'has_contacts'               =>  $this->contacts_count > 5,
            'contacts_count'            =>  $this->contacts_count,
            'estimates'                  => ProviderEstimateResource::collection($this->estimates),
            "created_at"                 => $this->created_at->diffForHumans(),
        ];
    }

    public function maskEmail(string $email)
    {


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

        $phoneLength = strlen($phone);

        if ($phoneLength <= 3) {
            return str_repeat('*', $phoneLength);
        }

        $visiblePart = substr($phone, -3);
        $maskedPart  = str_repeat('*', $phoneLength - 3);
        return $maskedPart . $visiblePart;
    }
}
