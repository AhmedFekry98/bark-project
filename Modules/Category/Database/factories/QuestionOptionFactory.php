<?php

namespace Modules\Category\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Category\Entities\QuestionOption::class;

    protected $counter = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->counter += 1;

        return [
            'value'                 => "Option $this->counter",
            'increment_credits'     => $this->faker->randomElement([2, 5, 10, 15]),
        ];
    }
}

