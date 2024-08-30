<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\ServiceRequest;

class ServiceRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $customers = User::whereHas('roles', fn($q) => $q->where('name', 'customer'))
            ->get();

        foreach ($customers as $customer) {
            ServiceRequest::factory(3)->create([
                'user_id'   => $customer->id
            ]);
        }
    }
}
