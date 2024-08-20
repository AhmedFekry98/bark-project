<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'phone_number'       => $this->faker->phoneNumber(),
            'email'             => $this->faker->safeEmail(),
            'password'          => Hash::make('password'),
            'extra'             => [
                'address'   => $this->faker->address(),
                'location'  => $this->faker->url()
            ],
            'verified_At'       => $this->faker->dateTime()
        ];
    }
}

