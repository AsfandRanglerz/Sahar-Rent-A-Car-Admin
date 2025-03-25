<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use App\Models\CarDetails;
use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use Illuminate\Support\Carbon;
use App\Models\UserLoyaltyEarning;
use Illuminate\Support\Facades\Log;
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
        
// Assign loyalty points if available for this car
// $loyaltyPoints = LoyaltyPoints::where('car_id', $request->car_id)->first();
// if ($loyaltyPoints) {
//     LoyaltyPoints::create([
//         'car_id' => $request->car_id,
//         'earned_points' => $loyaltyPoints->earned_points, // Apply the car's points
//         'on_car' => $loyaltyPoints->on_car,
//         'discount' => $loyaltyPoints->discount,
//         'user_id' => Auth::id(),
//     ]);
// }
$userId = Auth::id();

// Fetch the actual ID from car_details using the car_id provided in the request
$carDetails = CarDetails::where('car_id', $request->car_id)->first();

if ($carDetails) {
    // Now, use the correct ID to find loyalty points
    $loyaltyPoints = LoyaltyPoints::where('car_id', $carDetails->id)->first();
    Log::info("Loyalty Points Lookup for Car ID: {$carDetails->id}", ['loyaltyPoints' => $loyaltyPoints]);

    if ($loyaltyPoints) {
        // Find user's existing record
        $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->first();

        if ($userLoyalty) {
            // Update existing record
            Log::info("User exists. Adding points: " . $loyaltyPoints->on_car);
            $userLoyalty->increment('total_points', $loyaltyPoints->on_car);
            Log::info("Updated total points: " . $userLoyalty->total_points);
        } else {
            // Create a new record if not exists
            Log::info("User does not exist. Creating new entry with points: " . $loyaltyPoints->on_car);
            UserLoyaltyEarning::create([
                'user_id' => $userId,
                'total_points' => $loyaltyPoints->on_car,
                'earned_points' => $loyaltyPoints->on_car,
            ]);
        }
        
    } else {
        Log::info("No Loyalty Points found for car_id: {$carDetails->id}");
    }
} else {
    Log::info("No car found in car_details for car_id: " . $request->car_id);
}

        return response()->json([
            // 'status' => true,
            'message' => 'Booking created successfully and moved directly to Bookings.',
            'data' => $booking,
        ], 200);
    } 
    else {
        // Otherwise, create a request booking
        $requestBooking = RequestBooking::create([
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
        ]);
        
// Assign loyalty points if available for this car
    $loyaltyPoints = LoyaltyPoints::where('car_id', $request->car_id)->first();
    if ($loyaltyPoints) {
        LoyaltyPoints::create([
            'car_id' => $request->car_id,
            'earned_points' => $loyaltyPoints->earned_points, // Apply the car's points
            'on_car' => $loyaltyPoints->on_car,
            'discount' => $loyaltyPoints->discount,
            'user_id' => Auth::id(),
        ]);
    }
        return response()->json([
            // 'status' => true,
            'message' => 'Booking request created successfully and sent for approval.',
            'data' => $requestBooking,
        ], 200);
    }
}

public function getUserBookings()
{
    $userId = Auth::id(); // Get authenticated user ID

    $bookings = Booking::where('user_id', $userId)
        ->where('status', 0) // Fetch only active bookings
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($booking) {
            // $car = CarDetails::find($booking->car_id);
            $car = CarDetails::where('car_id', $booking->car_id)->first();
            return [
                'car_name' => $car ? $car->car_name : 'Car Not Found',
                'car_id' => $booking ? $booking->car_id : null,
                'booking_date' => $booking->created_at ? Carbon::parse($booking->created_at)->format('d M Y') : null,
                'booking_time' => $booking->created_at ? Carbon::parse($booking->created_at)->format('h:i A') : null,
                'price_per_hour' => $car ? number_format($car->pricing, 2) . ' AED' : 'Price Not Available',
                'price_per_day' => $car ? number_format($car->sanitized, 2) . ' AED' : 'Not Available',
                'price_per_week' => $car ? number_format($car->car_feature, 2) . ' AED' : 'Not Available',
            ];
        });

    return response()->json([
        'bookings' => $bookings
    ]);
}

public function UserHistoryBookings()
{
    $userId = Auth::id(); // Get authenticated user ID

    $bookings = Booking::where('user_id', $userId)
        ->where('status', 1) // Fetch only active bookings
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($booking) {
            // $car = CarDetails::find($booking->car_id);
            $car = CarDetails::where('car_id', $booking->car_id)->first();
            return [
                'car_name' => $car ? $car->car_name : 'Car Not Found',
                'car_id' => $booking ? $booking->car_id : null,
                'booking_date' => $booking->created_at ? Carbon::parse($booking->created_at)->format('d M Y') : null,
                'booking_time' => $booking->created_at ? Carbon::parse($booking->created_at)->format('h:i A') : null,
                'price_per_hour' => $car ? number_format($car->pricing, 2) . ' AED' : 'Price Not Available',
                'price_per_day' => $car ? number_format($car->sanitized, 2) . ' AED' : 'Not Available',
                'price_per_week' => $car ? number_format($car->car_feature, 2) . ' AED' : 'Not Available',
            ];
        });

    return response()->json([
        'history' => $bookings
    ]);
}
}
