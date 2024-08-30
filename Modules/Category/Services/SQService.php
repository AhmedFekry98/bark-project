<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\ServiceRequest;

class SQService
{
    public static $model = ServiceRequest::class;


    public function getRequests()
    {
        try {
            $requests = self::$model::query()
                // where city
                //  where services
                ->get();


            return Result::done($requests);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function getLeadRequests()
    {
        try {


            $providerr = auth()->user();

            $ignoredIds = $providerr->ignoredRequests->pluck('id');

            $requests = self::$model::query()
                // where city
                //  where services
                ->whereNotIn('id', $ignoredIds)
                ->get();


            return Result::done($requests);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function ignoreRequest(string $serviceRequestId)
    {
        try {
            $serviceRequest = ServiceRequest::find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }

            $provider = auth()->user();

            $provider->ignoredRequests()->create([
                'service_request_id'    => $serviceRequest->id
            ]);


            return Result::done(true);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }


    public function storeRequest(TDO $tdo)
    {
        try {
            $creationData = $tdo->all(asSnake: true);
            $creationData['user_id'] = auth()->id();

            $serviceRequest = self::$model::create($creationData);

            return Result::done($serviceRequest);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function hireProvider(string $serviceRequestId, TDO $tdo)
    {
        try {
            $serviceRequest = ServiceRequest::find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }

            $serviceRequest->hired_id = $tdo->hiredId;
            $serviceRequest->save();



            return Result::done($serviceRequest->provider);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
