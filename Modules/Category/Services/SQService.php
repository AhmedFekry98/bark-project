<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\Entities\User;
use Modules\Category\Emails\EstimateEmail;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\Estimate;
use Modules\Category\Entities\Question;
use Modules\Category\Entities\Service;
use Modules\Category\Entities\ServiceRequest;
use Modules\Category\Filters\StatusFilter;
use Modules\Category\Notifications\AcceptEstimateNotification;
use Modules\Category\Notifications\NewEstimateNotification;
use Modules\Category\Notifications\NewRequestNotification;
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
                ->where('is_approved', true)
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

            if ($provider->credits < $serviceRequest->total_credits) {
                return Result::error("Your credits < $serviceRequest->total_credits");
            }

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

            $serviceRequest->customer->notify(new NewEstimateNotification($estimate));


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
            $creationData = collect($tdo->asSnake())
                ->except(['answers'])
                ->toArray();

            $creationData['user_id'] = Auth::id();

            $answers = collect($tdo->answers)->map(function ($answerData) {
                $question = Question::find($answerData['questionId']);
                if (! $question) return null;

                $data = [
                    'question_id'   => $question->id,
                    'question_text' => $question->question_text,
                ];

                if (isset($answerData['optionId'])) {
                    $option = $question->options()->find($answerData['optionId']);
                    if (! $option) return null;
                    $data['option_id']          = $option->id;
                    $data['answer_text']        = $option->value;
                    $data['increment_credits']  = $option->increment_credits;
                } else {
                    $data['answer_text']        = $answerData['answerText'];
                }

                return $data;
            })->filter(fn($value) => $value != null);

            $increment_credits  =  $answers->sum('increment_credits');

            $creationData['questions_data'] = $answers->toArray();

            $serviceRequest = self::$model::create($creationData);
            $service = $serviceRequest->service;
            $total_credits = $service->credits + $increment_credits;

            $serviceRequest->is_approved        = ! $service->is_offline;
            $serviceRequest->total_credits      =  $total_credits;
            $serviceRequest->save();

            if (! $serviceRequest->is_approved ) {
                $admins = User::whereHas('roles', fn ($q) => $q->whereName('admin'))->get();
                Notification::send($admins, new NewRequestNotification($serviceRequest) );
            }

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

                // get total of request credits
                $total_credits = $estimate->request->total_credits;
                if ($estimate->request->provider->credits < $total_credits) return Result::error("provider has no due credits");

                // decrement due credits from provider account.
                $estimate->request->provider->decrement('credits', $total_credits);

                // creae transaction by value of $total_credits
                // ...


                $estimate->request->estimates()
                    ->whereNot('id', $estimate->id)
                    ->update([
                        'status' => 'rejected'
                    ]);

                    $estimate->request->provider->notify( new AcceptEstimateNotification($estimate) );
            }




            return Result::done($estimate);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
