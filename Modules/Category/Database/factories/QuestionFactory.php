<?php

namespace Modules\Category\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Entities\Question;
use Modules\Category\Entities\QuestionOption;

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

        return [
            'question_text'     => $this->faker->unique()->words(asText: true),
            'question_note'     => $this->faker->unique()->words(asText: true),
            'type'              => $type,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Question $question) {
            if (in_array($question->type, ['radio', 'checkbox'])) {
                QuestionOption::factory(4)->create([
                    'question_id'   => $question->id
                ]);
            }
        });
    }
}
