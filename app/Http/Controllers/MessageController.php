<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //
    public function index($chat_room_id)
    {
        $messages = Message::with('user')
            ->where('chat_room_id', $chat_room_id)
            ->get();
        $chat = Chat::with('participantOne', 'participantTwo')->where('id', $chat_room_id)->first();

        $chatWith = null;
        if ($chat) {
            $currentUserId = auth()->id();
            $chatWith = $chat->participant_1 === $currentUserId
                ? $chat->participantTwo->name
                : $chat->participantOne->name;
        }

        if (request()->ajax()) {
            return response()->json([
                'messages' => $messages
            ]);
        }

        return view('chat-room.index', [
            'messages' => $messages,
            'chat_room_id' => $chat_room_id,
            'chatWith' => $chatWith,
        ]);
    }

    public function store($chat_room_id)
    {
        request()->validate([
            'message' => ['required'],
        ]);

        $user_id = auth()->id();

        Message::create([
            'participant_id' => $user_id,
            'chat_room_id' => $chat_room_id,
            'message' => request('message'),
        ]);

        return response()->json(request('message'));
    }
}
