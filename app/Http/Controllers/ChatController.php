<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $chats = Chat::where('participant_1', $userId)
            ->orWhere('participant_2', $userId)
            ->latest()
            ->paginate(10);


        return view('chat.index', [
            'chats' => $chats,
            'chatWith' => $chats->map(function ($chat) use ($userId) {
                return $chat->participant_1 === $userId
                    ? $chat->participantTwo->name
                    : $chat->participantOne->name;
            })
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string',
        ]);

        $user = User::where('name', $data['name'])->first();

        if (!$user) {
            return redirect()->back()->withErrors(['name' => 'User not found']);
        }

        $currentUserId = auth()->id();

        $chatExists = Chat::where(function ($query) use ($currentUserId, $user) {
            $query->where('participant_1', $currentUserId)
                ->where('participant_2', $user->id);
        })->orWhere(function ($query) use ($currentUserId, $user) {
            $query->where('participant_1', $user->id)
                ->where('participant_2', $currentUserId);
        })->exists();

        if ($chatExists) {
            return redirect('/chats');
        }

        $chat_room_id = str()->random(26);
        Chat::create([
            'id' => $chat_room_id,
            'participant_1' => $currentUserId,
            'participant_2' => $user->id,
        ]);

        return redirect('/chat-room/' . $chat_room_id)->with('message', 'Chat created successfully!');
    }
}
