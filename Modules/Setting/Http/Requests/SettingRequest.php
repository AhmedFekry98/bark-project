<?php

namespace Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'socialmediaLinks'      => ['required', 'array', 'min:1'],
            'socialmediaLinks.*'    => ['required', 'url'],
            'footerLinks'           => ['nullable', 'array', 'min:1'],
            'footerLinks.*'         => ['nullable', 'url'],
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
