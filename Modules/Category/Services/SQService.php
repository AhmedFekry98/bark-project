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


    public function storeRequest(TDO $tdo)
    {
        try {
            $creationData =$tdo->all(asSnake: true);

            $serviceRequest = self::$model::create($creationData);

            return Result::done($serviceRequest);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
