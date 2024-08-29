<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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

        ServiceRequest::factory(5)
            ->create([
                'service_id' => random_int(1, 5),
                'user_id'   => 1,
            ]);
    }
}
