<?php

namespace Modules\Category\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\ServiceRequest;
use Modules\Category\Http\Requests\EstimateStatusRequest;
use Modules\Category\Http\Requests\HireProviderRequest;
use Modules\Category\Http\Requests\SendEstimateRequest;
use Modules\Category\Http\Requests\StatusRequest;
use Modules\Category\Http\Requests\StoreServiceRequest;
use Modules\Category\Http\Requests\StoreSQRequest;
use Modules\Category\Services\SQService;
use Modules\Category\Transformers\LeadCollection;
use Modules\Category\Transformers\LeadServiceRequestResource;
use Modules\Category\Transformers\ProviderEstimateResource;
use Modules\Category\Transformers\ProviderResource;
use Modules\Category\Transformers\ServiceRequestCollection;
use Modules\Category\Transformers\ServiceRequestResource;

class ServiceRequestController extends Controller
{
    use ApiResponses;

    public function __construct(
        private SQService $SQService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->SQService->getRequests();

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get all services reuqests successfuly",
            data: ServiceRequestResource::collection($result->data)
        );
    }

    public function indexLeads()
    {
        $result = $this->SQService->getLeadRequests();

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get all leads successfuly",
            data: LeadCollection::make($result->data)
        );
    }

    public function indexContacts()
    {
        $result = $this->SQService->getContactRequests();

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get contacts successfuly",
            data: ServiceRequestCollection::make($result->data)
        );
    }

    public function contactRequest(string $id)
    {
        $result = $this->SQService->contactRequest($id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Added Request to contacts successfuly",
            data: $result->data
        );
    }

    public function sendEstimate(string $id, SendEstimateRequest $request)
    {
        $result = $this->SQService->sendEstimate($id, TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Send Estimate successfuly",
            data: $result->data
        );
    }

    public function ignoreRequest(string $id)
    {
        $result = $this->SQService->ignoreRequest($id);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Ignored Request successfuly",
            data: $result->data
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreSQRequest $request)
    {
        $result = $this->SQService->storeRequest(TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Created a service request successfuly",
            data: ServiceRequestResource::make($result->data)
        );
    }

    public function status(string $serviceRequestId, StatusRequest $request)
    {
        $result = $this->SQService->updateStatus($serviceRequestId, TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Update request status successfuly",
            data: ServiceRequestResource::make($result->data)
        );
    }

    public function estimateStatus(string $estimateId, EstimateStatusRequest $request)
    {
        $result = $this->SQService->updateEstimateStatus($estimateId, TDOFacade::make($request));

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Update request status successfuly",
            data: ProviderEstimateResource::make($result->data)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function showRequest($id)
    {
        $result = $this->SQService->getRequestById($id);

        if ( $result->isError() ) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get request dat successfuly",
            data: ServiceRequestResource::make($result->data)
        );
    }

    public function showLead($id)
    {
        $result = $this->SQService->getRequestById($id);

        if ( $result->isError() ) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get lead request data successfuly",
            data: LeadServiceRequestResource::make($result->data)
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
