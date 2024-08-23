<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\Category;

class ProviderService
{
    public static $model = User::class;


    public function getProvidersFor(string $serviceId)
    {
        try {
            $providers = self::$model::query()
                ->where('service_id', $serviceId)
                ->get();

            return Result::done($providers);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
