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
            "hiredId"       => ['required', 'integer', 'exists:users,id'],

            "questionsData"             => ['required', 'array', 'min:1'],
            "questionsData.*.id"       => ['required', 'integer', 'exists:questions,id'],
            "questionsData.*.text"     => ['required', 'string', 'min:1', 'max:200'],
            "questionsData.*.value"    => ['required', 'string', 'min:1', 'max:200'],
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
