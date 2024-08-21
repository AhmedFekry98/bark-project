<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\CategoryQuestion;

class CategoryTableSeeder extends Seeder
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
        $categoryQuestionFactory = CategoryQuestion::factory();


        $categoryFactory
            ->count(6)
            ->has($categoryQuestionFactory->count(3))
            ->create();
    }
}
