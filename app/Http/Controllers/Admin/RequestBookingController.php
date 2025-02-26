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
        $requestbookings = RequestBooking::orderBy('status','ASC')->get();
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
    if (!$requestBooking->car_id) {
        do {
            $carId = mt_rand(1000, 9999);
        } while (RequestBooking::where('car_id', $carId)->exists());

        $requestBooking->car_id = $carId;
    }

    $requestBooking->driver_id = $request->driver_id;
    $requestBooking->status = 0; //  '0' means assigned
    $requestBooking->save();

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

    public function store(Request $request)
    {
        // return $request;
        // $validatedData = $request->validated();

        

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // } else {
        //     $image = 'public/admin/assets/images/avator.png';
        // }

        // do {
        //     $carId = mt_rand(1000, 9999); //  Can be replaced this with a custom logic for 4-digit IDs
        // } while (Booking::where('car_id', $carId)->exists());

        $status = 1;

        // Create the user
        $requestbooking = RequestBooking::create([
            'car_id' => $carId,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'self_pickup' => $request->self_pickup,
            'pickup_address' => $request->pickup_address,
            'pickup_date' => $request->pickup_date,
            // 'whatsapp_number' => $request->whatsapp_number,
            'pickup_time' => $request->pickup_time,
            'self_dropoff' => $request->self_dropoff,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => $request->dropoff_time,
            'driver_required' => $request->driver_required,
            'driver_name' => $request->driver_name,
            // 'car_play' => $request->features ? implode("\n", $request->features) : null, // Convert array to string
            // 'car_feature' => $request->car_feature,
            // 'delivery' => $request->delivery,
            // 'pickup' => $request->pickup,
            // 'travel_distance' => $request->travel_distance,
            // 'image' => $image,
            'status' => $request->status

        ]);
        // if (Auth::guard('subadmin')->check()) {
        //     SubAdminLog::create([
        //         'subadmin_id' => Auth::guard('subadmin')->id(),
        //         'section' => 'Bookings',
        //         'action' => 'Add',
        //         'message' => 'Added Booking: ' . $request->full_name,
        //     ]);
        // }
        // Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $generatedPassword));

        return redirect()->route('requestbooking.index')->with(['message' => 'Booking Created Successfully']);
    } 

    public function destroy(Request $request, $id){
     $requestbooking = RequestBooking::find($id);
        $requestbookingName = $requestbooking->name;
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
