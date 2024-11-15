<?php

namespace Modules\Credits\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Credits\Http\Requests\CreditPricingPlanRequest;
use Modules\Credits\Services\CreditPricingPlanService;

class CreditPricingPlanController extends Controller
{
    use ApiResponses;

    public function __construct(
        private CreditPricingPlanService $pricingPlans
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->pricingPlans->getAllPlans();
        if ( $result->isError() ) {
            return $this->badResponse(null, $result->errorMessage);
        }

        return $this->okResponse(
            $result->data,
            'Success APICall'
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreditPricingPlanRequest $request)
    {
        $result = $this->pricingPlans->storeCreditPricingPlan(TDOFacade::make($request));
        if ( $result->isError() ) {
            return $this->badResponse(null, $result->errorMessage);
        }

        return $this->okResponse(
            $result->data,
            'Success APICall'
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CreditPricingPlanRequest $request, $id)
    {
        $result = $this->pricingPlans->updateCreditPricingPlan(TDOFacade::make($request), $id);
        if ( $result->isError() ) {
            return $this->badResponse(null, $result->errorMessage);
        }

        return $this->okResponse(
            $result->data,
            'Success APICall'
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $result = $this->pricingPlans->deleteCreditPricingPlanById($id);
        if ( $result->isError() ) {
            return $this->badResponse(null, $result->errorMessage);
        }

        return $this->okResponse(
            $result->data,
            'Success APICall'
        );
    }
}
