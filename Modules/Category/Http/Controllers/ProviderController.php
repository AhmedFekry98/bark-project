<?php

namespace Modules\Category\Http\Controllers;

use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Routing\Controller;
use Modules\Category\Services\ProviderService;
use Modules\Category\Transformers\ProviderCollection;

class ProviderController extends Controller
{
    use ApiResponses;


    public function __construct(
        private ProviderService $providerService
    ) {}

    public function categoryProviders(string $serviceRequestId)
    {
        $result = $this->providerService->getProvidersFor($serviceRequestId);

        if ($result->isError()) {
            return $this->badResponse(
                message: $result->errorMessage
            );
        }

        return $this->okResponse(
            message: "Get all service providers successfuly",
            data: ProviderCollection::make($result->data)
        );
    }
}
