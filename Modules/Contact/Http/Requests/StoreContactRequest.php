<?php

namespace Modules\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'min:1', 'max:200'],
            'phone'     => ['required', 'string', 'min:9', 'max:16'],
            'email'     => ['required', 'email', 'max:100'],
            'message'   => ['required', 'string', 'min:1', 'max:500'],
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
