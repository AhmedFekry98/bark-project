<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEstimateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "price"             => ['required', 'numeric', 'min:1.0'],
            "estimatedTime"     =>  ['required', 'string', 'in:hour,visit,day,weak,month'],
            "addationalNotes"   => ['nullable', 'string', 'min:1', 'max:500'],
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
