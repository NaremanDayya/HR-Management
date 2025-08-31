<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Show all notifications for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // Mark a specific notification as read
    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        if ($request->user()->id === $notification->notifiable_id) {
            $notification->markAsRead();
        }

        return response()->json(['message' => 'Notification marked as read']);
    }

    // Mark all unread notifications as read
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }

    public function open(Request $request)
    {
        $notification = $request->user()->notifications()->find($request->query('nid'));

        if ($notification && isset($notification->data['url'])) {
            // Mark it as read if not already
            if (!$notification->read_at) {
                $notification->markAsRead();
            }

            // Redirect to the stored URL
            return redirect($notification->data['url']);
        }

        // Fallback
        return redirect()->route('notifications.index');
    }
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        return view('partials._notifications_list', compact('notifications'))->render();
    }
}
