<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUserNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications; // Retrieve all notifications
        $user->unreadNotifications->markAsRead();

        // Mark them as read if you want (optional)
        // $user->unreadNotifications->markAsRead();

        return view('notifications.index', [
            'notifications' => $notifications
        ]);
    }

    public function getUnreadNotificationsCount()
    {
        $user = auth()->user();

        $unreadNotifications = $user->unreadNotifications; // Retrieve only unread notifications

        $unreadCount = $unreadNotifications->count(); // Count unread notifications
        $response = [
            'unread_count' => $unreadCount,
        ];

        return response()->json($response);
    }
}
