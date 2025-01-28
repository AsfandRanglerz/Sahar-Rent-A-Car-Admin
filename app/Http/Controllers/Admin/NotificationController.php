<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Driver;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    // public function index()
    // {
    //     $notifications = Notification::orderBy('id', 'ASC')->get();
    //     return view('admin.notification.index',compact('notifications'));
    // }

    public function create()
    {
        $customers = User::all();
        $drivers = Driver::all();
        // return $customers;
        return view('admin.notification.create',compact('customers','drivers'));
    }

    public function store(Request $request)
    {
        // return $request;
        $status = '0';
    
        // Check if `customer_name` and `drivers` are arrays
        $customerNames = is_array($request->customer_name) ? $request->customer_name : [$request->customer_name];
        $drivers = is_array($request->drivers) ? $request->drivers : [$request->drivers];
    
        // Iterate through the arrays and create notifications
        foreach ($customerNames as $customerName) {
            foreach ($drivers as $driver) {
                Notification::create([
                    'user_type' => $request->user_type,
                    'customer_name' => $customerName, // Save as a single value
                    'drivers' => $driver, // Save as a single value
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
            }
        }
    
        return redirect()->route('notification.create')->with(['message' => 'Notifications Created Successfully']);
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

    // public function destroy($id)
    // {
    //     Notification::destroy($id);
    //     return redirect()->route('notification.index')->with(['message' => 'Notification Deleted Successfully']);

    // }
}
