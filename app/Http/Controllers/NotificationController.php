<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        // Đánh dấu tất cả là đã đọc
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}

