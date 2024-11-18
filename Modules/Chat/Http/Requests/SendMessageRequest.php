<?php

namespace Modules\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userId'        => 'required|integer|exists:users,id',
            'content'       => 'required|string|min:1|max:500',
            'attachments.*' => 'required|file|mimes:jpg,png,mp4,doc,pdf|max:20480', // Max 20MB
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
