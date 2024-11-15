<?php

namespace Modules\Credits\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Credits\Entities\CreditPricingPlan;

class CreditPricingPlanService
{
    public static $model = CreditPricingPlan::class;

    public function getAllPlans()
    {
        try {
            $creditPricingPlans = self::$model::get();

            return Result::done($creditPricingPlans);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function storeCreditPricingPlan(TDO $tdo)
    {
        try {
            $creditPricingPlan = self::$model::create($tdo->asSnake());

            return Result::done($creditPricingPlan);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function getCreditPricingPlanById(mixed $id)
    {
        try {
            $creditPricingPlan = self::$model::find($id);
            if (! $creditPricingPlan) {
                return Result::error("No Record with id '$id'");
            }

            return Result::done($creditPricingPlan);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function updateCreditPricingPlan(TDO $tdo, mixed $id)
    {
        try {
            $result = $this->getCreditPricingPlanById($id);
            if ( $result->isError() ) return $result;
            $creditPricingPlan = $result->data;

            $creditPricingPlan->update($tdo->asSnake());

            return Result::done($creditPricingPlan);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function deleteCreditPricingPlanById(mixed $id)
    {
        try {
            $result = $this->getCreditPricingPlanById($id);
            if ( $result->isError() ) return $result;
            $creditPricingPlan = $result->data;

            return Result::done($creditPricingPlan->delete());
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
