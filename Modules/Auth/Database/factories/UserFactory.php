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
            'name'        => $this->faker->name(),
            'phone'       => $this->faker->phoneNumber(),
            'city_id'        => fake()->randomElement([15420, 38514]),
            'email'      => $this->faker->safeEmail(),
            'password'   => Hash::make('password'),
            'verified_at'       => $this->faker->randomElement([now(), null]),
        ];
    }
}
