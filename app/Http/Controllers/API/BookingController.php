<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $booking = Booking::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pickup_address' => $request->pickup_address,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => $request->dropoff_time,
            'driver_required' => filter_var($request->driver_required, FILTER_VALIDATE_BOOLEAN),
        ]);

        // Return a JSON response
        return response()->json([
            'status' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 200);
    }

}
