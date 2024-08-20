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
        return [
            'firstName'         => ['required','string', 'min:3', 'max:25'],
            'lastName'          => ['required','string', 'min:3', 'max:25'],
            'phoneNumber'      => ['required', 'string', 'min:9', 'max:13'],
            'email'              => ['required', 'email', 'min:5', 'max:100', 'unique:users,email'],
            'password'          => ['required','string', 'min:6', 'max:16'],
            'asRestaurant'      => ['required', 'boolean'],
            'restaurantName'    => ['exclude_if:asRestaurant,false', 'required', 'string', 'min:1', 'max:50'],
            'extra.*'           => ['nullable']
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
