<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $role = $this->route('role');

        return array_merge(
            [
                'name'              => ['required', 'string', 'min:3', 'max:25'],
                'phone'             => ['required', 'string', 'min:9', 'max:13'],
                'email'             => ['required', 'email', 'min:5', 'max:100', 'unique:users,email'],
                'password'          => ['required', 'string', 'min:6', 'max:16'],
                'cityId'            => ['nullable', 'integer', 'exists:cities,id'],
            ],

            $role == 'provider' ? [
                "serviceId"       =>  ['required', 'integer', 'exists:services,id'],
                'companyName'       => ['required', 'string'],
                'companyWebsite'    => ['nullable', 'url'],
                'companySize'       => ['required', 'string'],
            ]: [],

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
