<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\DeleteRequest;
use App\Models\DriverLocation;
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

        return response()->json([
            'message' => 'Availability status updated successfully',
            'is_available' => $driver->is_available
        ], 200);
    }

    public function deactivateAccount(Request $request)
{
    $driverId = Auth::id(); // Get authenticated driver

    // Store the deactivation request
    DeleteRequest::create([
        'driver_id' => $driverId,
        'deactivation_date' => Carbon::now()->toDateString(), // Store current date
    ]);

    return response()->json([
        'message' => 'Your account will be deleted in 14 days.',
    ], 200);
}
}
