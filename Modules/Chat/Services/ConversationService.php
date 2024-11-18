<?php

namespace Modules\Chat\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Entities\User;
use Modules\Chat\Entities\Conversation;

class ConversationService
{

    public function getChatsForAuth(): Result
    {
        $conversations = Conversation::query()
            ->whereHas('members', fn($q) => $q->where('user_id', Auth::id()))
            ->with('members', fn($q) => $q->whereNot('user_id', Auth::id()))
            ->with('messages', fn($q) => $q->latest()->limit(1))
            ->limit(100)
            ->get();


        // proccessing the conversations to generate chats.
        $chats = $conversations->map(function (Conversation $conversation) {
            $user = $conversation->members->first();
            return (object) [
                'user_id'            => $conversation->id,
                'name'          => $user->name,
                'with_samary'   => true,
                'samary'        => $conversation->messages->first(),
                'conversation'  => $conversation,
                'with_messages' => false,
            ];
        });

        return  Result::done($chats);
    }

    public function getChatData(string|int $userId): Result
    {
        // find in users by id or return null
        $user = User::find($userId);

        // user if null make error result to handle it in controller
        if (! $user) {
            return Result::error("No user with id $userId");
        }

        // defient the chat object
        $chat       = new \stdClass;
        $chat->user_id = $user->id;
        $chat->name = $user->name;

        // get current authenticated user to use.
        $authUser = Auth::user();

        // get conversation between them.
        $conversation = $authUser->conversations()
            ->whereHas('members', fn($q) => $q->whereUserId($user->id))
            ->first();

        // set more info about chat object
        $chat->has_conversation = $conversation ? true : false;
        $chat->with_messages    = $chat->has_conversation;
        $chat->conversation     = $conversation;

        $chat->with_samary = false;
        $chat->samary      = null;

        return Result::done($chat);
    }

    public function sendMessage(TDO $tdo): Result
    {
        $result = $this->getChatData($tdo->userId);
        if ($result->isError()) return $result;
        // if valid result git chat from data
        $chat = $result->data;

        if (! $chat->has_conversation) {
            $chat->conversation = Auth::user()
                ->conversations()
                ->create();

            $chat->conversation->members()
                ->attach([Auth::id(), $tdo->userId]);
        }

        $message = $chat->conversation->messages()->create([
            'user_id' => Auth::id(),
            'content' => $tdo->content,
        ]);

        // get attachments
        $attachments = $tdo->attachments ?? [];

        if (! empty($attachments)) {
            foreach ($attachments as $attachment) $message->addMedia($attachment)
                ->toMediaCollection('attachments');
        }

        return Result::done($message);
    }
}
