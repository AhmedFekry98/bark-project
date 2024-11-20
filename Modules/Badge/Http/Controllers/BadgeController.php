<?php

namespace Modules\Badge\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Badge\Http\Requests\StoreBadgeRequest;
use Modules\Badge\Http\Requests\UpdateBadgeRequest;
use Modules\Badge\Services\BadgeService;
use Modules\Badge\Transformers\BadgeResource;

class BadgeController extends Controller
{
    use ApiResponses;

    public function __construct(
        private BadgeService $badgeService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->badgeService->getAllBadges();
        if ($result->isError()) {
            return $this->badResponse([], $result->errorMessage);
        }

        return $this->okResponse(BadgeResource::collection($result->data), 'Get badges successfuly');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreBadgeRequest $request)
    {
        $result = $this->badgeService->storeBadge(
            TDOFacade::make($request)
        );

        if ($result->isError()) {
            return $this->badResponse([], $result->errorMessage);
        }

        return $this->createdResponse([], 'Badge created successfuly');
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
    public function update(UpdateBadgeRequest $request, $id)
    {
        $result = $this->badgeService->updateBadge(
            $id,
            TDOFacade::make($request)
        );

        if ($result->isError()) {
            return $this->badResponse([], $result->errorMessage);
        }

        return $this->okResponse([], 'Badge updated successfuly');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $result = $this->badgeService->deleteBadge($id);
        if ($result->isError()) {
            return $this->badResponse([], $result->errorMessage);
        }

        return $this->okResponse([], 'Badge deleted successfuly');
    }
}
