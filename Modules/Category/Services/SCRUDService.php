<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Auth\Entities\Profession;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\Service;
use Modules\Category\Filters\CityIdFilter;
use Modules\Category\Filters\LimitFilter;
use Modules\Category\Filters\SearchFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SCRUDService
{
    public static $model = Service::class;



    public function getServices()
    {
        try {
            $query = self::$model::query();


            $services = QueryBuilder::for($query)
                ->latest()
                ->AllowedFilters([
                    AllowedFilter::custom('limit', new LimitFilter),
                    AllowedFilter::custom('search', new SearchFilter),
                    AllowedFilter::custom('cityId', new CityIdFilter)
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
                'questions',
                'professionName',
            ])->toArray();

            // create profession if not sent the id
            if (!isset($creationData['profession_id']) && $tdo->professionName) {
                $profession = Profession::create(['name' => $tdo->professionName]);
                $creationData['profession_id'] = $profession->id;
            }

            $service = self::$model::create($creationData);

            // create questions.
            if ($tdo->questions) {
                $questions = $tdo->questions;

                foreach ($questions as $index => $questionData) {

                    if (in_array($questionData['type'], ['checkbox', 'radio']) && !isset($questionData['options'])) {
                        return Result::error("questions.$index.options is required");
                    }

                    $question = $service->questions()->create([
                        'question_text'  => $questionData['questionText'],
                        'question_note'  => $questionData['questionNote'] ?? null,
                    ]);


                    if (isset($questionData['options']) && !empty($questionData['options'])) {
                        $optionsData = collect($questionData['options'] ?? [])->map(function ($optionData) {
                            return [
                                'value'                 => $optionData['value'],
                                'increment_credits'     => $optionData['incrementCredits'] ?? 0
                            ];
                        })->toArray();

                        $question->options()->createMany($optionsData);
                    }
                }
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
                'questions',
                'professionName'
            ])->toArray();

            // create profession if not sent the id
            if (!isset($creationData['profession_id']) && $tdo->professionName) {
                $profession = Profession::create(['name' => $tdo->professionName]);
                $updateData['profession_id'] = $profession->id;
            }

            $service->update($updateData);


            // upload category image.
            if ($tdo->image) {
                $service->addMedia($tdo->image)
                    ->toMediaCollection('image');
            }

            // re-create questions.
            $service->questions()->delete();
            if ($tdo->questions) {
                $questions = $tdo->questions;

                foreach ($questions as $index => $questionData) {

                    if (in_array($questionData['type'], ['checkbox', 'radio']) && !isset($questionData['options'])) {
                        return Result::error("questions.$index.options is required");
                    }

                    $question = $service->questions()->create([
                        'question_text'  => $questionData['questionText'],
                        'question_note'  => $questionData['questionNote'] ?? null,
                    ]);


                    if (isset($questionData['options']) && !empty($questionData['options'])) {
                        $optionsData = collect($questionData['options'] ?? [])->map(function ($optionData) {
                            return [
                                'value'                 => $optionData['value'],
                                'increment_credits'     => $optionData['incrementCredits'] ?? 0
                            ];
                        })->toArray();

                        $question->options()->createMany($optionsData);
                    }
                }
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
