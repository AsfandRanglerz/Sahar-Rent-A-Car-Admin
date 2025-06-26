<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Driver;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\NotificationJob;
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

public function sendNotification(Request $request)
    {
         $request->validate([
            'receiver_id' => 'required|exists:drivers,id',
            'sender_id'   => 'required|exists:users,id',
        ]);
        
        $sender = User::find($request->sender_id);
        $receiver = Driver::find($request->receiver_id);

       

    $uploadedImagePath = null;

    // Use your provided upload code
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $uploadedImagePath = 'public/admin/assets/images/users/' . $filename;
    }
        $data = [
        'receiver_id'   => $receiver->id,
        'sender_id'     => $sender->id,
        'sender_name'   => $sender->name,
        // 'sender_image'  => $sender->image ? asset($sender->image) : null,
        'image'         => $uploadedImagePath,
        'message'       => $request->message,
        ];

        // Dispatch FCM job
        dispatch(new NotificationJob(
            $receiver->fcm_token,
            $request->title,
            $request->message,
            $data
        ));

        return response()->json(['message' => 'Notification sent successfully']);
    }

    public function driversendNotification(Request $request)
    {
         $request->validate([
            'sender_id' => 'required|exists:drivers,id',
            'receiver_id'   => 'required|exists:users,id',
        ]);
        
        $sender = Driver::find($request->sender_id);
        $receiver = User::find($request->receiver_id);

       

    $uploadedImagePath = null;

    // Use your provided upload code
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $uploadedImagePath = 'public/admin/assets/images/users/' . $filename;
    }
        $data = [
        'receiver_id'   => $receiver->id,
        'sender_id'     => $sender->id,
        'sender_name'   => $sender->name,
        // 'sender_image'  => $sender->image ? asset($sender->image) : null,
        'image'         => $uploadedImagePath,
        'message'       => $request->message,
        ];

        // Dispatch FCM job
        dispatch(new NotificationJob(
            $receiver->fcm_token,
            $request->title,
            $request->message,
            $data
        ));

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
