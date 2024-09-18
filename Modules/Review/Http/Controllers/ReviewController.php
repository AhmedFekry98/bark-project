<?php

namespace Modules\Review\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Review\Http\Requests\StoreReviewRequest;
use Modules\Review\Http\Requests\UpdateReviewRequest;
use Modules\Review\Services\ReviewService;
use Modules\Review\Transformers\ProviderRatingResource;
use Modules\Review\Transformers\ReviewResource;

class ReviewController extends Controller
{
    use ApiResponses;

    public function __construct(
        private ReviewService $reviewService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->reviewService->getProvidersWithRating();

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get providers with rating successfuly",
            data: ProviderRatingResource::collection($result->data)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreReviewRequest $request)
    {
        $result = $this->reviewService->storeReview(TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Created Review successfuly",
            data: ReviewResource::make($result->data)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateReviewRequest $request, $id)
    {
        $result = $this->reviewService->updateReview(TDOFacade::make($request), $id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Updated Review successfuly",
            data: ReviewResource::make($result->data)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
