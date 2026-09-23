<?php

// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // 1) Logged-in user ke sare chats (customer ya SP)
    public function index()
    {
        $userId = Auth::id();

        $chats = Chat::query()
            ->where('user_id', $userId)
            ->orWhere('sp_id', $userId)
            ->with(['user:id,name', 'sp:id,name', 'lastMessage'])
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $chats,
        ]);
    }

    // 2) Customer (auth user) -> SP ke sath chat start/get
    public function startOrGet(Request $request)
    {
        $request->validate([
            'sp_id' => 'required|exists:users,id|not_in:' . Auth::id(),
        ]);

        $userId = Auth::id();
        $spId   = (int) $request->sp_id;

        $chat = Chat::firstOrCreate([
            'user_id' => $userId,
            'sp_id'   => $spId,
        ]);

        return response()->json([
            'status' => true,
            'data'   => $chat->load(['user:id,name', 'sp:id,name', 'lastMessage']),
        ]);
    }

    // 3) Ek chat ke messages (pagination)
    public function show(Chat $chat, Request $request)
    {
        $this->authorizeChat($chat);

        $perPage = (int) $request->get('per_page', 50);

        $messages = $chat->messages()
            ->with('sender:id,name')
            ->orderBy('id')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data'   => $messages,
        ]);
    }

    // 4) Message bhejna
    public function send(Request $request, Chat $chat)
    {
        $this->authorizeChat($chat);

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'chat_id'   => $chat->id,
            'sender_id' => Auth::id(),
            'message'   => $request->message,
        ]);

        $chat->update([
            'last_message_id' => $message->id,
        ]);

        // Agar app realtime WebSocket use karegi to ye line kaam aayegi
        event(new MessageSent($message->load('sender', 'chat')));

        return response()->json([
            'status'  => true,
            'data'    => $message,
            'message' => 'Message sent',
        ]);
    }

    // 5) Messages ko read mark karna (optional)
    public function markAsRead(Chat $chat)
    {
        $this->authorizeChat($chat);

        $userId = Auth::id();

        $chat->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Marked as read',
        ]);
    }

    protected function authorizeChat(Chat $chat): void
    {
        $userId = Auth::id();

        if ($chat->user_id !== $userId && $chat->sp_id !== $userId) {
            abort(403, 'Unauthorized');
        }
    }
}
