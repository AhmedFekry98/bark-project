<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // call development table seeders if not production.
        if ( config('app.env') != 'production' ) {
            $this->call([
                CategoryTableSeeder::class
                // ...
            ]);
        }
    }
}
