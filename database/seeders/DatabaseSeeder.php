<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // call modules seeders here.
        $this->call([
            AuthDatabaseSeeder::class,
            CategoryDatabaseSeeder::class,
            // ...
        ]);
    }
}
