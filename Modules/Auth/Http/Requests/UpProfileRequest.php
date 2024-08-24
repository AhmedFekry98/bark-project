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

        return array_merge(
            [
                'name'              => ['nullable', 'string', 'min:3', 'max:25'],
                'phone'             => ['nullable', 'string', 'min:9', 'max:13'],
                'email'             => ['nullable', 'email', 'min:5', 'max:100', 'unique:users,email'],
                'password'          => ['nullable', 'string', 'min:6', 'max:16'],
            ],

            $role == 'provider' ? [
                "serviceId"       =>  ['nullable', 'integer', 'exists:services,id'],
                'companyName'       => ['nullable', 'string'],
                "serviceData"       => ['nullable', 'array', 'min:1'],
                "serviceData.*.id"  => ['required', 'integer', 'exists:questions,id'],
                "serviceData.*.text"  => ['required', 'string'],
                "serviceData.*.value"  => ['required'],
                'companyWebsite'    => ['nullable', 'url'],
                'companySize'       => ['nullable', 'string'],
            ] : [],

            [
                'sales'     => ['nullable', 'boolean'],
                'social'    => ['nullable', 'boolean'],
            ]

        );
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
