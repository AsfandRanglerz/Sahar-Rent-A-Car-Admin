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
            ->select('id','title', 'description', 'created_at','seenByUser')
            ->orderBy('created_at', 'desc')
            ->get();

        // if ($notifications->isEmpty()) {
        //     return response()->json(['message' => 'No notifications found'], 404);
        // }
        $formattedNotifications = $notifications->map(function ($notification) use ($user) {
            return [
                'id' => $notification->id,
                'user_id' => $user,
                'title'       => $notification->title,
                'description' => $notification->description,
                'time'        => Carbon::parse($notification->created_at)->format('h:i A'), // Format time as 12-hour (12:00 PM)
                'created_at' => Carbon::parse($notification->created_at),
                'is_seen'     => (bool) $notification->seenByUser,
            ];
        });
        return response()->json([
            'notifications' => $formattedNotifications
        ]);
    }

    public function getDriverNotifications(Request $request)
    {
        $user = Auth::id(); // Get the authenticated user

        // if (!$user) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        // Fetch notifications based on user_type
         $notifications = Notification::where(function ($query) use ($user) {
            $query->orWhere('driver_id', $user);
        })
            ->select('id','title', 'description', 'created_at','seenByUser')
            ->orderBy('created_at', 'desc')
            ->get();

        // if ($notifications->isEmpty()) {
        //     return response()->json(['message' => 'No notifications found'], 404);
        // }
        $formattedNotifications = $notifications->map(function ($notification) use ($user) {
            return [
                'id' => $notification->id,
                'user_id' => $user,
                'title'       => $notification->title,
                'description' => $notification->description,
                'time'        => Carbon::parse($notification->created_at)->format('h:i A'), // Format time as 12-hour (12:00 PM)
                'created_at' => Carbon::parse($notification->created_at),
                'is_seen'     => (bool) $notification->seenByUser,
            ];
        });
        return response()->json([
            'notifications' => $formattedNotifications
        ]);
    }

    public function showNotification($id)
    {
        $user = Auth::id(); 
        $notification  = Notification::find($id);
        if(!$notification){
            return response()->json(['message' => 'Notification not found'], 404);
        }
        
        if(!$notification->seenByUser){
            $notification->seenByUser = true;
            $notification->save();
        }

        return response()->json([
           'data' => $notification
            
        ]);
    }

    public function clearAll()
{
    $userId = Auth::id();

    // Delete all notifications for the authenticated user
    Notification::where('customer_id', $userId)
    ->orwhere('driver_id', $userId)
    ->delete();

    return response()->json([
        'message' => 'Notifications cleared successfully'
    ]);
}

public function Driverclearall()
{
    $userId = Auth::id();

    // Delete all notifications for the authenticated user
    Notification::orwhere('driver_id', $userId)
    ->delete();

    return response()->json([
        'message' => 'Notifications cleared successfully'
    ]);
}
}
