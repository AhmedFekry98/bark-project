<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\Service;
use Modules\Category\Filters\LimitFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SCRUDService
{
    public static $model = Service::class;


    public function getServices()
    {
        try {
            $services = QueryBuilder::for(self::$model)
                ->latest()
                ->AllowedFilters([
                    AllowedFilter::custom('limit', new LimitFilter)
                ])
                ->with('questions')
                ->get();

            return Result::done($services);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function storeService(TDO $tdo)
    {
        try {
            $creationData = collect(
                $tdo->all(asSnake: true)
            )->except([
                'questions'
            ])->toArray();

            $service = self::$model::create($creationData);

            // create questions.
            if ($tdo->questions) {
                $questions = collect($tdo->questions)
                    ->map(function ($question) {
                        $question['question_text']  = $question['questionText'];
                        $question['question_note']  = $question['questionNote'];
                        return $question;
                    })->toArray();

                $service->questions()
                    ->createMany($questions);
            }

            // upload category image.
            if ($tdo->image) {
                $service->addMedia($tdo->image)
                    ->toMediaCollection('image');
            }

            return Result::done($service);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function getServiceById(string $serviceId)
    {
        try {
            $service = self::$model::find($serviceId);

            if (! $service) {
                return Result::error("No service with id '$serviceId'");
            }

            return Result::done($service);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function updateService(string $serviceId, TDO $tdo)
    {
        try {
            // get category by id with error handlling with cover dry consepts.
            $result = $this->getServiceById($serviceId);
            if ($result->isError()) return $result; // if has an error stop and go to controller to handle it.
            $service = $result->data; // get model from result object.

            $updateData = collect(
                $tdo->all(asSnake: true)
            )->except([
                'questions'
            ])->toArray();

            $service->update($updateData);


            // upload category image.
            if ($tdo->image) {
                $service->addMedia($tdo->image)
                    ->toMediaCollection('image');
            }

            // get last version of category.
            $service = self::$model::find($serviceId);

            return Result::done($service);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function deleteServiceById(string $serviceId)
    {
        try {
            // get category by id with error handlling with cover dry consepts.
            $result = $this->getServiceById($serviceId);
            if ($result->isError()) return $result; // if has an error stop and go to controller to handle it.
            $service = $result->data; // get model from result object.

            // delete category.
            $deleted = $service->delete();

            return Result::done($deleted);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
