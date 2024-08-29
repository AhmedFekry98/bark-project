<?php

namespace Modules\Auth\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\UpProfileRequest;
use Modules\Auth\Services\ProfileService;
use Modules\Auth\Transformers\UserResource;

class ProfileController extends Controller
{
  use ApiResponses;

  /**
   * Initialize services here.
   */
  public function __construct(
    private ProfileService $profileService
  ) {
  }

  /**
   * Disply listing of Resources
   */
  public function index(string $role)
  {
    $result = $this->profileService->getAllProfiles($role);

    if ($result->isError()) {
      return $this->badResponse(
        message: $result->errorMessage
      );
    }

    return $this->okResponse(
      data: UserResource::collection($result->data),
      message: __('success_messages.get_data')
    );
  }


  public function show()
  {
    $result = $this->profileService->currentProfile();

    if ($result->isError()) {
      return $this->badResponse(
        message: $result->errorMessage
      );
    }

    return $this->okResponse(
      UserResource::make($result->data),
      $messge = "Get Profile Data Successfuly."
    );
  }

  public function update(UpProfileRequest $request)
  {
    $result = $this->profileService->update(TDOFacade::make($request));

    if ($result->isError() ) {
      return $this->badResponse(
        $message = $result->errorMessage
      );
    }

    return $this->okResponse(
      UserResource::make($result->data),
      $messge = "Updated profile data successfuly."
    );
  }
}
