<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'limit' => 'nullable|integer',
            'unread_only' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($request->userId);

        $limit = $request->input('limit', 20);
        $unreadOnly = $request->boolean('unread_only', false);

        $query = $user->notifications();

        if ($unreadOnly) {
            $query->whereNull('read_at');
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'data' => $notifications
        ]);
    }
    public function getUnreadCount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $count = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }
    public function markAsRead(Request $request, $notificationId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $notification = $user->notifications()->findOrFail($notificationId);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }



}
