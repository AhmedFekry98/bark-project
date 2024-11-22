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
            "categoryId"        => ['required', 'integer', 'exists:categories,id'],
            'professionName'    => ['required_without:professionId', 'string', 'min:1', 'max:200'],
            "professionId"      => ['required_without:professionName', 'integer', 'exists:professions,id'],
            "image"         => ['required', 'image', 'max:4096'],
            'credits'       => ['required', 'integer', 'min:1'],
            'isOffline'     => ['required', 'boolean'],


            // questions rules.
            "questions"                   => ['required', 'array', 'min:1'],
            "questions.*.questionText"    => ['required', 'string', 'min:1', 'max:200'],
            "questions.*.questionNote"    => ['nullable', 'string', 'min:1', 'max:200'],
            "questions.*.type"            => ['required', 'string', "in:$types"],
            "questions.*.options"         => ['nullable', 'array'],
            "questions.*.options.*.value"   => ['required'],
            "questions.*.options.*.incrementCredits" => ['nullable', 'integer', 'min:1'],
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
