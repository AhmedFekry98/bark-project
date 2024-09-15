<?php

namespace Modules\Setting\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Services\SettingService;
use Modules\Setting\Transformers\SettingResource;
use Psy\TabCompletion\Matcher\FunctionDefaultParametersMatcher;

class SettingController extends Controller
{
    use ApiResponses;

    public function __construct(
        private SettingService $settingService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $result = $this->settingService->getSetting();

        if ( $result->isError() ) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get settings successfuly",
            data: SettingResource::make($result->data)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SettingRequest $request)
    {
        $result = $this->settingService->updateSetting(TDOFacade::make($request));

        if ( $result->isError() ) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Put settings successfuly",
            data: SettingResource::make($result->data)
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
