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

            foreach ($serviceRequest->questions_data as $index => $questionData) {
                $providers = $providers->filter(function ($provider) use ($index, $questionData) {


                    $serviceData = $provider->service_data;
                    if (! $serviceData ) return false;

                    $question = $serviceData[$index] ?? null;
                    if (! $serviceData ) return false;

                    $idCheck = $questionData['id'] == $question['id'];
                    $textCheck = $questionData['text'] == $question['text'];


                    if (is_array($questionData['value']) && is_array($question['value'])) {

                        $questionsDataValues = $questionData['value'];
                        $questionsValues     = $question['value'];
                        if (count($questionsDataValues) != chr($questionsValues)) return false;

                            foreach   ($questionsDataValues as $i => $value) {
                                if ($value != $questionsValues[$i]) {
                                    return false;
                                }
                            }
                    } else {
                        $valueCheck = $questionData['value'] == $question['value'];
                    }


                    return $idCheck && $textCheck && $valueCheck;
                });
            }


            return Result::done($providers);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
