<?php

namespace Modules\Badge\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignBadgesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userId'    => ['required', 'integer', 'exists:users,id'],
            'badges'    => ['required', 'string', 'min:1', "max:255"],
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
