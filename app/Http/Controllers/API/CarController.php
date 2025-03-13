<?php

namespace App\Http\Controllers\API;

use App\Models\CarDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

    // Optional status filtering
    $status = $request->query('status');

    // Fetch cars belonging to the authenticated user
    $cars = CarDetails::where('user_id', $userId) // 'user_id' column exists in 'car_details'
       ->where('status', 0)
        ->select(['id','car_id', 'car_name', 'pricing',  'passengers', 'luggage', 'doors', 'car_type','car_play','sanitized','car_feature',  'image'])
        ->get();

    return response()->json([
        'cars' => $cars
    ]);
    }

    public function getCarPriceDetails(Request $request)
    {
        $userId = Auth::id(); // Get logged-in user ID
        
        // if (!$userId) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        $carId = $request->input('id');
        $carDetails = CarDetails::
        select(['car_id', 'pricing', 'sanitized', 'car_feature'])
        ->where('id', $carId)
        ->first();

        // if (!$carDetails) {
        //     return response()->json(['message' => 'Car details not found'], 404);
        // }

        return response()->json([
            'price_details' => $carDetails,
            // 'user_id' => $userId, // Include the authenticated user ID in the response
            // 'car_id' => $carDetails->car_id,
            // 'hourly_price' => $carDetails->pricing,  // Assuming JSON format
            // 'daily_price' => $carDetails->sanitized,
            // 'weekly_price' => $carDetails->car_feature, // Assuming JSON format
        ]);
    }
}
