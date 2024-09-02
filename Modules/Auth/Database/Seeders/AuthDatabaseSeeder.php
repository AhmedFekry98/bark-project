<?php

namespace Modules\Auth\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\Profession;
use Modules\Auth\Entities\Role;
use Modules\Auth\Entities\User;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        $roles = config('roles', []);


        foreach ($roles as $name => $abilities) {
            Role::factory()->create([
                'name'      => $name,
                'abilities' => $abilities,
            ]);

            $user = User::factory()->create([
                'email'         => "$name@example.com",
                'company_name'  => $name == 'provider' ? fake()->name() : null,
                'company_website'  => $name == 'provider' ? fake()->url() : null,
                'company_size'  => $name == 'provider' ? 'self-emploee' : null,
            ]);

            $user->assignRole($name);

            if ($name == 'provider') {
                $user->professions()->attach([1]);
            }
        }

        User::factory(3)
            ->create([

            ])
            ->each(fn($customer) => $customer->assignRole('customer'));

        $this->call([
            ProfessionTableSeeder::class
        ]);
    }
}
