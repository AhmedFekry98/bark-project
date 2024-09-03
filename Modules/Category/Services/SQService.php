<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\ServiceRequest;
use Modules\Category\Filters\StatusFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SQService
{
    public static $model = ServiceRequest::class;

    public static function query(): Builder
    {
        $user = auth()->user();

        return self::$model::query()
            ->latest()
            ->when(
                $user->role != 'admin',
                fn($q) => $q->where('created_at', '>', now()->subDays(6))
            );
    }


    public function getRequests()
    {
        try {
            $user = auth()->user();
            $query = self::query();

            if ($user->role != 'admin') {
                $query->where('user_id', $user->id);
            }

            $requests = $query->get();


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

            $requests = self::query()
                ->withCount('contacts')
                // check if in professions.
                ->whereHas(
                    'service',
                    fn($q) => $q->whereIn('profession_id', $providerr->professions->pluck('id'))
                )
                // check if not ignored.
                ->whereNotIn('id', $ignoredIds)
                ->get();


            return Result::done($requests);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function getContactRequests()
    {
        try {
            $provider = auth()->user();

            $requests = QueryBuilder::for(self::query())
                ->allowedFilters([
                    AllowedFilter::custom('status', new StatusFilter),
                ])
                ->whereHas('contacts', function ($q) use ($provider) {
                    $q->where('provider_id', $provider->id);
                })
                ->get();


            return Result::done($requests);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function contactRequest(string $serviceRequestId)
    {
        try {
            $serviceRequest = ServiceRequest::find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }

            $provider = auth()->user();

            $serviceRequest->contacts()->create([
                'provider_id'    => $provider->id
            ]);


            return Result::done(true);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function sendEstimate(string $serviceRequestId, TDO $tdo)
    {
        try {
            $serviceRequest = ServiceRequest::find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }

            $provider       = auth()->user();

            $canBeSend = $serviceRequest->contacts()
                ->where('provider_id', $provider->id)
                ->count();

            if (! $canBeSend) {
                return Result::error("Service request not in your contacts");
            }

            $isAlreadySent = $serviceRequest->estimates()
                ->where('provider_id', $provider->id)
                ->count();

            if ($isAlreadySent > 0) {
                return Result::error("Estimate already sent");
            }

            $estimateData  = $tdo->all(asSnake: true);
            $estimateData['provider_id'] = $provider->id;

            $serviceRequest->estimates()->create($estimateData);


            return Result::done(true);
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

    public function getRequestById(string $serviceRequestId)
    {
        try {
            $serviceRequest = ServiceRequest::query()
                ->with("estimates")
                ->find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }



            return Result::done($serviceRequest);
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

    public function updateStatus(string $serviceRequestId, TDO $tdo)
    {
        try {
            $serviceRequest = ServiceRequest::find($serviceRequestId);

            if (! $serviceRequest) {
                return Result::error("No service request with id '$serviceRequestId'");
            }

            $serviceRequest->status = $tdo->status;
            $serviceRequest->save();



            return Result::done($serviceRequest);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
