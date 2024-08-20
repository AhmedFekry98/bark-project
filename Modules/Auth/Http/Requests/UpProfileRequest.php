<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $role = $this->user()->role;
        $condationalRules = [];

        if ($role ==  'customer') {
            // $condationalRules['extra.address']           = ['nullable'];
        }

        if ($role == 'restaurant') {
            $condationalRules['openTime']           = ['nullable', 'string', 'date_format:h:i A'];
            $condationalRules['closeTime']          = ['nullable', 'string', 'date_format:h:i A'];
            $condationalRules['restaurantImage']    = ['nullable', 'image', 'max:4096'];
            $condationalRules['customDomain']       = ['nullable', 'url'];

            # socail media links.
            $condationalRules['socialmediaLinks.facebook']    = ['nullable', 'url', 'max:350'];
            $condationalRules['socialmediaLinks.twitter']     = ['nullable', 'url', 'max:350'];
            $condationalRules['socialmediaLinks.instagram']    = ['nullable', 'url', 'max:350'];
            $condationalRules['socialmediaLinks.youtube']    = ['nullable', 'url', 'max:350'];
            $condationalRules['socialmediaLinks.tiktok']      = ['nullable', 'url', 'max:350'];
        }


        return [
            'firstName'         => ['nullable', 'string', 'min:3', 'max:25'],
            'lastName'          => ['nullable', 'string', 'min:3', 'max:25'],
            'phoneNumber'      => ['nullable', 'string', 'min:9', 'max:13'],
            'email'              => ['nullable', 'email', 'min:5', 'max:100', 'unique:users,email'],
            'password'          => ['nullable', 'string', 'min:6', 'max:16'],
            // 'asRestaurant'      => ['nullable', 'boolean'],
            'restaurantName'    => ['exclude_if:asRestaurant,false', 'nullable', 'string', 'min:1', 'max:50'],
            'image'             => ['nullable', 'image'],
            'address'     => ['nullable'],
            'location'    => ['nullable', 'url'],

            # condational rules.
            ...$condationalRules
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
