<?php

namespace Modules\Review\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reviewableId'  => ['required', 'integer', 'exists:users,id'],
            'requestId'     => ['required', 'integer', 'exists:service_requests,id'],
            'comment'       =>  ['required', 'string', 'min:1', 'max:500'],
            'stars'         => ['required', 'integer', 'between:1,5'],
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
