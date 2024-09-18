<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Entities\User;
use Modules\Category\Emails\EstimateEmail;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\Estimate;
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
            )->with('estimates', function ($q) {
                $q->latest()
                    ->whereStatus('pending');
            });
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
                ->whereDoesntHave('estimates', fn($q) => $q->whereStatus('accepted'))
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

            if (auth()->user()->role != 'provider') {
                return Result::error('You are not a provider user');
            }

            $provider = auth()->user();

            // more logic here?
            // step 1: check  has due credits
            // step 2 : increment credits and make transaction by it.

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

            if ($isAlreadySent) {
                return Result::error("Estimate already sent");
            }

            if ($serviceRequest->acceptedEstimate) {
                return Result::error("This Request has accepted estimate");
            }

            if ($serviceRequest->estimates()->count() >= 5) {
                return  Result::error("This request has already 5 estimates");
            }


            $estimateData  = $tdo->all(asSnake: true);
            $estimateData['provider_id'] = $provider->id;
            $estimate = $serviceRequest->estimates()->create($estimateData);

            $customerEmail = $serviceRequest->customer->email;
            Mail::to($customerEmail)
                ->send(new EstimateEmail($estimate));


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
            $serviceRequest = self::query()
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

            // if ($serviceRequest->customer->id) {
            //     return Result::error("Canot be update status for this service request");
            // }

            $serviceRequest->status = $tdo->status;
            $serviceRequest->save();



            return Result::done($serviceRequest);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function updateEstimateStatus(string $estimateId, TDO $tdo)
    {
        try {
            $estimate = Estimate::find($estimateId);

            if (! $estimate) {
                return Result::error("No estimate with id '$estimateId'");
            }

            if (auth()->id() != $estimate->request->customer->id) {
                return Result::error("You not have a permisson to update the status for this estimate");
            }


            $estimate->status = $tdo->status;
            $estimate->save();


            if ($tdo->status == 'accepted') {
                $estimate->request->update([
                    // 'status'    => 'hired',
                    'hired_id'  => $estimate->provider_id
                ]);

                $estimate->request->estimates()
                    ->whereNot('id', $estimate->id)
                    ->update([
                        'status' => 'rejected'
                    ]);
            }




            return Result::done($estimate);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
