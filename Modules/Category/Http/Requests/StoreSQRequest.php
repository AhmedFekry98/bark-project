<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSQRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "serviceId"     => ['required', 'integer', 'exists:services,id'],
            // "hiredId"       => ['required', 'integer', 'exists:users,id'],
            "cityId"        => ['required', 'integer', 'exists:cities,id'],

            "answers"                   => ['required', 'array', 'min:1'],
            "answers.*.questionId"      => ['required', 'integer', 'exists:questions,id'],
            "answers.*.optionId"        => ['required', ],
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
