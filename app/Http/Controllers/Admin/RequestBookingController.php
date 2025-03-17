<?php

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use App\Models\Booking;
use App\Models\SubAdminLog;
use Illuminate\Http\Request;
use App\Models\RequestBooking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RequestBookingController extends Controller
{
    public function index()
    {
        
        // $bookings = Booking::latest()->get();
        $requestbookings = RequestBooking::whereIn('status', [0, 2])
        ->orderBy('status','ASC')->get();
        $drivers = Driver::all();
        return view('admin.RequestBooking.index',compact('requestbookings','drivers'));
    }

    public function edit(Request $request, $id)
    {
        // $request->validate([
        //     'request_booking_id' => 'required|exists:requestbookings,id',
        //     'driver_id' => 'required|exists:drivers,id',
        // ]);
    
        $requestBooking = RequestBooking::findOrFail($id);
    $driver = Driver::find($request->driver_id);

    // Validate if the driver exists
    if (!$driver) {
        return response()->json(['message' => 'Driver not found.'], 404);
    }

    // Check if the driver is already assigned at the same time
    $isDriverAssigned = RequestBooking::where('driver_id', $request->driver_id)
    ->where('status', '!=', 1) // Exclude completed bookings
    ->where(function ($query) use ($requestBooking) {
        $query->where('pickup_date', $requestBooking->pickup_date) // Same pickup date
              ->where(function ($q) use ($requestBooking) {
                  $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
                    ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
                    ->orWhere(function ($subQuery) use ($requestBooking) {
                        $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
                                 ->where('dropoff_time', '>', $requestBooking->dropoff_time);
                    });
              });
    })
    ->exists();

if ($isDriverAssigned) {
    return response()->json([
        'message' => 'This driver is already assigned for another booking during this time.'
    ], 400);
}


    // Generate a unique 4-digit car_id if it doesn't exist
    // if (!$requestBooking->car_id) {
    //     do {
    //         $carId = mt_rand(1000, 9999);
    //     } while (RequestBooking::where('car_id', $carId)->exists());

    //     $requestBooking->car_id = $carId;
    // }
    if ($request->car_id) {
        $requestBooking->car_id = $request->car_id;
    }

    $requestBooking->driver_id = $request->driver_id;
    $requestBooking->status = 0; //  '0' means assigned
    $requestBooking->save();
    $driver->is_available = false;
    $driver->save();
// **Check if booking is completed (status = 1), then free the driver**
    if ($requestBooking->status == 1) {
        $driver->is_available = true; // Mark driver as available
        $driver->save();
    }

    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;
    
        SubAdminLog::create([
            'subadmin_id' => $subadmin->id,
            'section' => 'Request Bookings',
            'action' => 'Assign Driver',
            'message' => "SubAdmin: {$subadminName} assigned Driver with Car ID: {$requestBooking->car_id} ",
        ]);
    }
    return response()->json([
        'success' => true,
        'message' => 'Driver assigned successfully!',
        'driver_name' => $driver->driver_name,
        'car_id' => $requestBooking->car_id
    ]);
    
    }
    // public function create(){
    //     return view('admin.booking.create');
    // }
   
    
    // public function store(Request $request)
    // {
        

    //     return redirect()->route('requestbooking.index')->with(['message' => 'Booking Created Successfully']);
    // } 

    public function destroy(Request $request, $id){
     $requestbooking = RequestBooking::find($id);
        $requestbookingName = $requestbooking->full_name;
    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;
        SubAdminLog::create([
            'subadmin_id' => Auth::guard('subadmin')->id(),
            'section' => 'Request Bookings',
            'action' => 'Delete',
            'message' => "SubAdmin: {$subadminName} deleted request booking: {$requestbookingName}",
        ]);
    }
    $requestbooking->delete();
    return redirect()->route('requestbooking.index')->with(['message' => 'Booking Deleted Successfully']);
    }
}
