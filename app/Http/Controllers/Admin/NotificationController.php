<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Driver;
use App\Models\SubAdminLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->get();
        $customers = User::all();
        $drivers = Driver::all();
        return view('admin.Notification.index',compact('notifications','customers','drivers'));
    }

    // public function create()
    // {
    //     $customers = User::all();
    //     $drivers = Driver::all();
    //     // return $customers;
    //     return view('admin.notification.create',compact('customers','drivers'));
    // }

    public function store(Request $request)
    {
        // return $request;
        $status = '0';
    
        // Check if `customer_name` and `drivers` are arrays
        // $customerNames = is_array($request->customer_name) ? $request->customer_name : [$request->customer_name];
        // $drivers = is_array($request->drivers) ? $request->drivers : [$request->drivers];
    
        // Iterate through the arrays and create notifications
        // foreach ($customerNames as $customerName) {
        //     foreach ($drivers as $driver) {
                Notification::create([
                    'user_type' => $request->user_type,
                    // 'customer_id' => $customerName, // Save as a single value
                    // 'driver_id' => $driver, // Save as a single value
                    'title' => $request->title,
                    'description' => $request->description,
                    'created_at' => now(),
                ]);
        //     }
        // }
        if (Auth::guard('subadmin')->check()) {
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'Notifications',
                'action' => 'Add',
                'message' => 'Added  Notification',
            ]);
       }
        return redirect()->route('notification.index')->with(['message' => 'Notifications Created Successfully']);
    }
    


    // public function edit($id)
    // {
    //     // return $id;
    //     $notification = Notification::find($id);

    //     return view('admin.notification.edit', compact('notification'));
    // }

    // public function update(Request $request, $id)
    // {
    //     // return $request;
    //     // Validate the incoming request data
    //     // $request->validate([
    //     //     'text' => 'required|string|max:255',
    //     //     'url' => 'nullable|url',
    //     //     'status' => 'required|boolean',
    //     // ]);

    //     // Find the existing header content by ID
    //     $notification = Notification::find($id);

    //     // Update the category
    //     $notification->update([
    //         'user_type' => $request->user_type,
    //         'customers' => $request->customers,
    //         'drivers' => $request->drivers,
    //         'title' => $request->title,
    //         'description' => $request->description,
    //     ]);

    //     return redirect()->route('notification.index')->with(['message' => 'Notification Updated Successfully']);
    // }

    public function destroy(Request $request, $id)
    {
         $notification = Notification::find($id);
        $notificationName = $notification->name;
        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'Notifications',
                'action' => 'Delete',
                'message' => "SubAdmin: {$subadminName} deleted Notification: {$notificationName}",
            ]);
        }
        $notification->delete();
        return redirect()->route('notification.index')->with(['message' => 'Notification Deleted Successfully']);

    }
}
