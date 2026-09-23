<?php

namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function chatView()
    {
        $chat = Chat::findOrFail(1);

        // Assume you're authenticating manually with tokens
        $token = 'zW3SsSiiVuc7TcNIWuLB7HtRtzvMQGFbNRjytkmLb74b939c';

        return view('web.chat', [
            'chat' => $chat,
            'token' => $token,
        ]);
    }

    public function start(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);
        $userOrVendor = $request->user();
        if ($userOrVendor instanceof \App\Models\User) {
            $user = $userOrVendor;
        } elseif ($userOrVendor instanceof \App\Models\Vendor) {
            $vendor = $userOrVendor;
        }

        // Determine who is authenticated and validate opposite party is provided
        if (@$user) {
            if (!$request->vendor_id) {
                return response()->json(['error' => 'vendor_id is required'], 422);
            }

            $chat = Chat::firstOrCreate([
                'user_id' => $user->id,
                'vendor_id' => $request->vendor_id,
            ]);
        } elseif (@$vendor) {
            if (!$request->user_id) {
                return response()->json(['error' => 'user_id is required'], 422);
            }

            $chat = Chat::firstOrCreate([
                'user_id' => $request->user_id,
                'vendor_id' => $vendor->id,
            ]);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json(['chat' => $chat]);
    }


    public function messages(Request $request, $chatId)
    {
        $chat = Chat::with('messages.sender')->findOrFail($chatId);

        $userOrVendor = $request->user();
        if ($userOrVendor instanceof \App\Models\User) {
            $user = $userOrVendor;
        } elseif ($userOrVendor instanceof \App\Models\Vendor) {
            $vendor = $userOrVendor;
        }

        if (($user && $chat->user_id !== $user->id) ||
            ($vendor && $chat->vendor_id !== $vendor->id)
        ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['messages' => $chat->messages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::findOrFail($request->chat_id);

        $userOrVendor = $request->user();
        if ($userOrVendor instanceof \App\Models\User) {
            $user = $userOrVendor;
        } elseif ($userOrVendor instanceof \App\Models\Vendor) {
            $vendor = $userOrVendor;
        }

        if ((@$user && $chat->user_id !== @$user->id) ||
            (@$vendor && $chat->vendor_id !== @$vendor->id)
        ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sender = $user ?? $vendor;

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => $message->load('sender')]);
    }
}
