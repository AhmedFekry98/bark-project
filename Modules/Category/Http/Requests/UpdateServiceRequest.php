<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Category\Entities\Question;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types = implode(',', Question::$types);

        return [
            "name"      => ['nullable', 'string'],
            "image"     => ['nullable', 'image', 'max:4096'],
            'credits'       => ['nullable', 'integer', 'min:1'],
            'isOffline'     => ['nullable', 'boolean'],


            // questions rules.
            "questions"                     => ['nullable', 'array', 'min:1'],
            "questions.*.questionText"      => ['required', 'string', 'min:1', 'max:200'],
            "questions.*.questionNote"      => ['nullable', 'string', 'min:1', 'max:200'],
            "questions.*.type"              => ['required', 'string', "in:$types"],
            "questions.*.options"           => ['nullable', 'array'],
            "questions.*.options.value"     => ['required'],
            "questions.*.options.credits"   => ['nullable', 'integer', 'min:1'],
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
