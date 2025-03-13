<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUserNotifications(Request $request)
    {
        $user = Auth::id(); // Get the authenticated user

        // if (!$user) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        // Fetch notifications based on user_type
         $notifications = Notification::where(function ($query) use ($user) {
            $query->where('customer_id', $user)
                  ->orWhere('driver_id', $user);
        })
            ->select('title', 'description', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // if ($notifications->isEmpty()) {
        //     return response()->json(['message' => 'No notifications found'], 404);
        // }
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'title'       => $notification->title,
                'description' => $notification->description,
                'time'        => Carbon::parse($notification->created_at)->format('h:i A') // Format time as 12-hour (12:00 PM)
            ];
        });
        return response()->json([
            'notifications' => $formattedNotifications
        ]);
    }
}
