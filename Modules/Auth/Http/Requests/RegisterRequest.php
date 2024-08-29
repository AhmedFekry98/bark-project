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
                'cityId'            => ['required', 'integer', 'exists:cities,id'],
            ],

            $role == 'provider' ? [
                "professions"       =>  ['required', 'array', 'min:1', 'max:5'],
                'professions.*'     => ['required', 'integer', 'exists:professions,id'],
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
