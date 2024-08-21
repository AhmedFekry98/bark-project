<?php

namespace Modules\Category\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryCollection;
use Modules\Category\Transformers\CategoryResource;

class CategoryController extends Controller
{
    use ApiResponses;

    public function __construct(
        private CategoryService $categoryService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->categoryService->getCategories();

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get all categories successfuly",
            data: CategoryCollection::make($result->data)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $result = $this->categoryService->storeCategory(TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Created a category successfuly",
            data: CategoryResource::make($result->data)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $result = $this->categoryService->getCategoryById($id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get a category successfuly",
            data: CategoryResource::make($result->data)
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $result = $this->categoryService->updateCategory($id, TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Updated a category successfuly",
            data: CategoryResource::make($result->data)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $result = $this->categoryService->deleteCategoryById($id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Deleted a category successfuly",
            data: $result->data
        );
    }
}
