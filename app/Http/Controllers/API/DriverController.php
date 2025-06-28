<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Driver;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\DeleteRequest;
use App\Models\DriverLocation;
use App\Models\DriverNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function storeDriverLocation(Request $request)
    {
        $driverId = Auth::id(); // Get logged-in driver ID

        // Validate the request
        // $request->validate([
        //     'address' => 'required|string|max:255',
        //     'latitude' => 'required|numeric',
        //     'longitude' => 'required|numeric',
        // ]);

        // Update or create the driver's location
        $location = DriverLocation::create(
            [
                'driver_id' => $driverId, // Search condition
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return response()->json([
            'message' => 'Location updated successfully',
            'data' => $location
        ], 200);
    }

    public function getDriverLocation()
    {
        $driverId = Auth::id(); // Get logged-in driver ID

        // Fetch the driver's location details
        $location = DriverLocation::where('driver_id', $driverId)->get();

        // Check if the location exists
        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }

        return response()->json([
            'driver_id' => $driverId,
           'location' => $location
        ], 200);
    }

    public function updateAvailability(Request $request)
    {
        $driver = Auth::user(); // Get logged-in driver

        // Validate input
        $request->validate([
            'is_available' => 'required|boolean' // Ensures it's either 1 or 0
        ]);
        $driver = Driver::find($driver->id);
        // Update driver's availability
        $driver->update(['is_available' => $request->is_available]);
        $status = $driver->is_available ? 'available' : 'unavailable';
        return response()->json([
            'message' => "You are now $status",
            'is_available' => $driver->is_available
        ], 200);
    }

    public function getAvailability()
{
    $driver = Auth::user(); // Authenticated driver

    // Make sure driver exists and has the 'is_available' field
    if (!$driver || !isset($driver->is_available)) {
        return response()->json([
            'message' => 'Driver not found or availability status not set',
        ], 404);
    }

    return response()->json([
        'message' => 'Driver availability status retrieved successfully',
        'is_available' => $driver->is_available
    ], 200);
}

    public function deleteAccount(Request $request)
{
    $driverId = Auth::id(); // Get authenticated driver
    $driver = Driver::find($driverId);
    // Store the deactivation request
    // DeleteRequest::create([
    //     'driver_id' => $driverId,
    //     'deactivation_date' => Carbon::now()->toDateString(), // Store current date
    // ]);
    $driver->delete();
    return response()->json([
        'message' => 'Your account has been deleted successfully',
    ], 200);
}

public function customerdeleteAccount(Request $request)
{
    $customerId = Auth::id(); // Get authenticated driver
    $customer = User::find($customerId);
    // Store the deactivation request
    // DeleteRequest::create([
    //     'user_id' => $customerId,
    //     'deactivation_date' => Carbon::now()->toDateString(), // Store current date
    // ]);
    $customer->delete();
    return response()->json([
        'message' => 'Your account has been deleted successfully',
    ], 200);
}

public function deactivateAccount(Request $request)
{
    $driver = Auth::user();

    // Store notification for admin
    $notification = new DriverNotification();
    $notification->driver_id = $driver->id;
    $notification->type = 'deactivation';
    $notification->message = "Driver {$driver->name} has requested account deactivation";
    $notification->save();

    return response()->json([
        'message' => 'Account deactivation request sent'
    ]);
}

public function customerdeactivateAccount(Request $request)
{
    $customer = Auth::user();

    // Store notification for admin
    $notification = new DriverNotification();
    $notification->user_id = $customer->id;
    $notification->type = 'deactivation';
    $notification->message = "Customer {$customer->name} has requested account deactivation";
    $notification->save();

    return response()->json([
        'message' => 'Account deactivation request sent'
    ]);
}

}
