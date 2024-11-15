<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Credits\Database\Seeders\CreditsDatabaseSeeder;
use Modules\World\Database\Seeders\WorldDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // call modules seeders here.
        $this->call([
            // WorldDatabaseSeeder::class,
            AuthDatabaseSeeder::class,
            CategoryDatabaseSeeder::class,
            CreditsDatabaseSeeder::class,
            // ...
        ]);
    }
}
