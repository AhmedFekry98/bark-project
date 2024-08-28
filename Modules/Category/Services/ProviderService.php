<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\ServiceRequest;

class ProviderService
{
    public static $model = User::class;


    public function getProvidersFor(string $serviceRequestId)
    {
        try {
            $serviceRequest = ServiceRequest::find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }

            $providers = self::$model::query()
                ->where('service_id', $serviceRequest->service_id)
                ->get();


            return Result::done($providers);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
