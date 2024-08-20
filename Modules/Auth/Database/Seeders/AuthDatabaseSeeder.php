<?php

namespace Modules\Auth\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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

        $roles = [
            'admin',
            'customer',
        ];


        foreach ($roles as $role) {
            Role::factory()->create([
                'name' => $role,
                'abilities' => config("roles.$role", []),
            ]);

            User::factory()
                ->create([
                    'email' => "$role@example.com",
                ])
                ->assignRole($role);
        }
    }
}
