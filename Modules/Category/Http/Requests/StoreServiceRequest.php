<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Category\Entities\Question;

class StoreServiceRequest extends FormRequest
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
            "categoryId"    => ['required', 'integer', 'exists:categories,id'],
            "name"          => ['required', 'string'],
            "image"         => ['required', 'image', 'max:4096'],

            // questions rules.
            "questions"                   => ['required', 'array', 'min:1'],
            "questions.*.questionText"   => ['required', 'string', 'min:1', 'max:200'],
            "questions.*.questionNote"   => ['nullable', 'string', 'min:1', 'max:200'],
            "questions.*.type"              => ['required', 'string', "in:$types"],
            "questions.*.details"   => ['nullable', 'array'],
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
