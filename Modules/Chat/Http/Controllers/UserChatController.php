<?php

namespace Modules\Chat\Http\Controllers;

use Graphicode\Standard\Facades\TDOFacade;
use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Chat\Http\Requests\SendMessageRequest;
use Modules\Chat\Services\ConversationService;
use Modules\Chat\Transformers\ChatResource;

class UserChatController extends Controller
{
    use ApiResponses;

    public function __construct(
        private ConversationService $conversationService
    ) {}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $resault = $this->conversationService->getChatsForAuth();
        if ($resault->isError()) {
            return $this->badResponse(null, $resault->errorMessage);
        }


        return $this->okResponse(ChatResource::collection($resault->data), 'Get chats successfuly');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SendMessageRequest $request)
    {
        $resault = $this->conversationService->sendMessage(
            TDOFacade::make($request)
        );

        if ($resault->isError()) {
            return $this->badResponse(null, $resault->errorMessage);
        }

        // broadcast message here
        // broadcast(new MessageSent($resault->data));


        return $this->okResponse(null, 'Message sent successfuly');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($userId)
    {
        $resault = $this->conversationService->getChatData($userId);
        if ( $resault->isError() ) {
            return $this->badResponse(null, $resault->errorMessage);
        }


        return $this->okResponse(ChatResource::make($resault->data), 'Get chat data successfuly');
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
