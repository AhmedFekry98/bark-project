<?php

namespace Modules\Credits\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Credits\Entities\CreditPricingPlan;

class CreditPricingPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $creditsPricingPlans = [
            [
                "name" => "Starter Package",
                "credits" => 20,
                "price" => 10
            ],
            [
                "name" => "Medium Package",
                "credits" => 40,
                "price" => 20
            ],
            [
                "name" => "Professional Package",
                "credits" => 80,
                "price" => 30
            ]
        ];


        foreach ($creditsPricingPlans as $plan) CreditPricingPlan::create($plan);
    }
}
