<?php

namespace Modules\Category\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Entities\Question;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Category\Entities\Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type       = $this->faker->randomElement(Question::$types);
        $details    = null;


        if (in_array($type, ['radio', 'checkbox'])) {
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

