<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = config('roles', [
            'admin' => ['*']
        ]);

        $name = $this->faker->randomElement(array_keys($roles));
        $abilities = $roles[$name];

        return [
            'name'      => $name,
            'abilities' => $abilities,
        ];
    }
}

