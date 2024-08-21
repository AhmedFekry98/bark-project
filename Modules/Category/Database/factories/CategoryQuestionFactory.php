<?php

namespace Modules\Category\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Entities\CategoryQuestion;

class CategoryQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Category\Entities\CategoryQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type       = $this->faker->randomElement(CategoryQuestion::$types);
        $details    = null;


        if (in_array($type, ['radios', 'checkboxs'])) {
            $details = [
                'items' => [
                    'item 1',
                    'item 2',
                    'item 3'
                ]
            ];
        }

        return [
            'question_text'     => $this->faker->unique()->words(asText: true),
            'question_note'     => $this->faker->unique()->words(asText: true),
            'type'              => $type,
            'details'           => $details,
        ];
    }
}
