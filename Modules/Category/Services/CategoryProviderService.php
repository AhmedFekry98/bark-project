<?php

namespace Modules\Category\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\Category;

class CategoryProviderService
{
    public static $model = User::class;


    public function getProvidersFor(string $categoryId)
    {
        try {
            $providers = self::$model::query()
                ->where('category_id', $categoryId)
                ->get();

            return Result::done($providers);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
