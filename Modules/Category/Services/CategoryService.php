<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Category\Entities\Category;

class CategoryService
{
    public static $model = Category::class;


    public function getCategories()
    {
        try {
            $categories = self::$model::get();

            return Result::done($categories);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function storeCategory(TDO $tdo)
    {
        try {
            $creationData = collect(
                $tdo->all(asSnake: true)
            )->except([
                'questions'
            ])->toArray();

            $category = self::$model::create($creationData);

            // create questions.
            if ($tdo->questions) {
                $questions = collect($tdo->questions)
                ->map(function($question) {
                    $question['question_text']  = $question['questionText'];
                    $question['question_note']  = $question['questionNote'];
                    return $question;
                })->toArray();

                $category->categoryQuestions()
                    ->createMany($questions);
            }

            // upload category image.
            if ($tdo->image) {
                $category->addMedia($tdo->image)
                    ->toMediaCollection('image');
            }

            return Result::done($category);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function getCategoryById(string $categoryId)
    {
        try {
            $category = self::$model::find($categoryId);

            if (! $category ) {
                return Result::error("No category with id '$categoryId'");
            }

            return Result::done($category);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function updateCategory(string $categoryId, TDO $tdo)
    {
        try {
            // get category by id with error handlling with cover dry consepts.
            $result = $this->getCategoryById($categoryId);
            if ( $result->isError() ) return $result; // if has an error stop and go to controller to handle it.
            $category = $result->data; // get model from result object.

            $updateData = collect(
                $tdo->all(asSnake: true)
            )->except([
                'questions'
            ])->toArray();

            $category->update($updateData);


            // upload category image.
            if ($tdo->image) {
                $category->addMedia($tdo->image)
                    ->toMediaCollection('image');
            }

            // get last version of category.
            $category = self::$model::find($categoryId);

            return Result::done($category);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function deleteCategoryById(string $categoryId)
    {
        try {
            // get category by id with error handlling with cover dry consepts.
            $result = $this->getCategoryById($categoryId);
            if ( $result->isError() ) return $result; // if has an error stop and go to controller to handle it.
            $category = $result->data; // get model from result object.

            // delete category.
            $deleted = $category->delete();

            return Result::done($deleted);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
