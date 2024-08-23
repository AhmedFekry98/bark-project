<?php

namespace Modules\Category\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Service;
use Modules\Category\Http\Requests\StoreServiceRequest;
use Modules\Category\Http\Requests\UpdateServiceRequest;
use Modules\Category\Services\SCRUDService;
use Modules\Category\Transformers\ServiceCollection;
use Modules\Category\Transformers\ServiceResource;

class ServiceController extends Controller
{
    use ApiResponses;

    public function __construct(
        private SCRUDService $sCRUDService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->sCRUDService->getServices();

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get all services successfuly",
            data: ServiceCollection::make($result->data)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreServiceRequest $request)
    {
        $result = $this->sCRUDService->storeService(TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Created a service successfuly",
            data: ServiceResource::make($result->data)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $result = $this->sCRUDService->getServiceById($id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get a service successfuly",
            data: ServiceResource::make($result->data)
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateServiceRequest $request, $id)
    {
        $result = $this->sCRUDService->updateService($id, TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Updated a service successfuly",
            data: ServiceResource::make($result->data)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $result = $this->sCRUDService->deleteServiceById($id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Deleted a service successfuly",
            data: $result->data
        );
    }
}
