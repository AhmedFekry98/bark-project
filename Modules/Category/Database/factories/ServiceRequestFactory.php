<?php

namespace Modules\Category\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Entities\Service;
use PHPUnit\Runner\ParameterDoesNotExistException;

class ServiceRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Category\Entities\ServiceRequest::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $services = Service::get();

        return [
            'service_id'     => $services->random()->id,
            'questions_data' => [
                [
                    'questionId'    => 1,
                    'text'          => "Question 1",
                    "value"         => "answer 2",
                ],
                [
                    'questionId'    => 2,
                    'text'          => "Question 2",
                    "value"         => [
                        'value 1',
                        'value 2'
                    ]
                ]
            ],
        ];
    }
}
