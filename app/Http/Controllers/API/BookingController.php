<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\RequestBooking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function createBooking(Request $request)
    {
        // Validate request data
        // $validateUser = Validator::make(
        //     $request->all(),[
        //     'full_name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'phone' => 'required|string|max:15',
        //     'pickup_address' => 'required|string|max:255',
        //     'pickup_date' => 'required|date',
        //     'pickup_time' => 'required|date_format:H:i',
        //     'dropoff_address' => 'nullable|string|max:255',
        //     'dropoff_date' => 'nullable|date',
        //     'dropoff_time' => 'nullable|date_format:H:i',
        //     'driver_required' => 'required|boolean',
        // ]);

        // Create the booking
    //     $booking = RequestBooking::create([
    //         'full_name' => $request->full_name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'self_pickup' => $request->self_pickup,
    //         'pickup_address' => $request->pickup_address,
    //         'pickup_date' => $request->pickup_date,
    //         'pickup_time' => $request->pickup_time,
    //         'self_dropoff' => $request->self_dropoff,
    //         'dropoff_address' => $request->dropoff_address,
    //         'dropoff_date' => $request->dropoff_date,
    //         'dropoff_time' => $request->dropoff_time,
    //         // 'driver_required' => filter_var($request->driver_required, FILTER_VALIDATE_BOOLEAN),
    //         'driver_required' => $request->driver_required,
    //     ]);

    //     // Return a JSON response
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Booking created successfully',
    //         'data' => $booking,
    //     ], 200);
    // }

    if ($request->self_pickup === "Yes" && $request->self_dropoff === "Yes") {
        $booking = Booking::create([
        'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'self_pickup' => $request->self_pickup,
            'pickup_address' => $request->pickup_address,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'self_dropoff' => $request->self_dropoff,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => $request->dropoff_time,
            'driver_required' => $request->driver_required,
            'car_id' => $request->car_id,
            'status' => 0, // Directly set to Active
        ]);

        return response()->json([
            // 'status' => true,
            'message' => 'Booking created successfully and moved directly to Bookings.',
            'data' => $booking,
        ], 200);
    } 
    else {
        // Otherwise, create a request booking
        $requestBooking = RequestBooking::create([
            // 'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'self_pickup' => $request->self_pickup,
            'pickup_address' => $request->pickup_address,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'self_dropoff' => $request->self_dropoff,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => $request->dropoff_time,
            'driver_required' => $request->driver_required,
            'car_id' => $request->car_id,
        ]);

        return response()->json([
            // 'status' => true,
            'message' => 'Booking request created successfully and sent for approval.',
            'data' => $requestBooking,
        ], 200);
    }
}
}
