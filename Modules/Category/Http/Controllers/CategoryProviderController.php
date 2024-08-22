<?php

namespace Modules\Category\Http\Controllers;

use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Services\CategoryProviderService;
use Modules\Category\Transformers\CategoryProviderCollection;

class CategoryProviderController extends Controller
{
    use ApiResponses;


    public function __construct(
        private CategoryProviderService $categoryProviderService
    ) {}

    public function categoryProviders(string $categoryId)
    {
        $result = $this->categoryProviderService->getProvidersFor($categoryId);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get all category providers successfuly",
            data: CategoryProviderCollection::make($result->data)
        );
    }
}
