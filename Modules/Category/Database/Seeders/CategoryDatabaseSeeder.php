<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\Question;
use Modules\Category\Entities\Service;

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

        $categoryFactory = Category::factory();

        $serviceFactory = Service::factory();

        $questionFactory = Question::factory();

        $categoryFactory->count(6)
            ->has(
                $serviceFactory->count(3)
                    ->has(
                        $questionFactory->count(4)
                    )
            )->create();
    }
}
