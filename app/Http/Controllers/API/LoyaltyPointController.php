<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use App\Models\UserLoyaltyEarning;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoyaltyPointController extends Controller
{
//     public function getLoyaltyPoints()
// {
//     $userId = Auth::id(); // Get the logged-in user ID

//     if (!$userId) {
//         return response()->json([
//             'message' => 'User not authenticated',
//         ], 401);
//     }

//     // Join loyalty_points → bookings → car_inventory
//     $loyaltyPoints = LoyaltyPoints::with('booking.car')
//     ->whereHas('booking', function ($query) use ($userId) {
//         $query->where('user_id', $userId);
//     })->get();

//     $requestBookings = RequestBooking::where('user_id', $userId)->get();

// $data = $loyaltyPoints->map(function ($point) {
//     return [
//         'car_name' => $point->booking->car->car_name ?? 'N/A',
//         'on_car' => $point->on_car,
//         'discount' => $point->discount,
//     ];
// });

// $requestBookingData = $requestBookings->map(function ($requestBooking) {
//     return [
//         'car_name' => $requestBooking->car->car_name ?? 'N/A',
//         'on_car' => $requestBooking->on_car, // No loyalty points for request bookings
//         'discount' => $requestBooking->discount, // No discount unless linked to loyalty points
//     ];
// });

// // Combine both results
// $history = $data->merge($requestBookingData);

// return response()->json([
//     'total_points' => $loyaltyPoints->sum('earned_points'),
//     'history' => $history,
// ]);

// }
public function getLoyaltyPoints()
{
    $userId = Auth::id(); // Get the logged-in user ID

    if (!$userId) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    // Get all bookings and request bookings for the user
    // $bookings = Booking::where('user_id', $userId)->pluck('car_id');
    // $requestBookings = RequestBooking::where('user_id', $userId)->pluck('car_id');

    // // Merge both car_ids (avoid duplicates)
    // $carIds = $bookings->merge($requestBookings)->unique();
    $bookings = Booking::with('car')->where('user_id', $userId)->get();
    $requestBookings = RequestBooking::with('car')->where('user_id', $userId)->get();

    // Extract unique car IDs
    $carIds = $bookings->pluck('car.id')->merge($requestBookings->pluck('car.id'))->unique();

    // Get loyalty points for these cars
    $loyaltyPoints = LoyaltyPoints::with('car')->whereIn('car_id', $carIds)->get();

    // Format response
    $data = $loyaltyPoints->map(function ($point) {
        return [
            'car_name' => $point->car->car_name ?? 'N/A',
            'on_car' => $point->on_car,
            'discount' => $point->discount,
        ];
    });

    return response()->json([
        'total_points' => $loyaltyPoints->sum('on_car'),
        'history' => $data,
    ]);
}

public function earnLoyaltyPoints(Request $request)
{
    $userId = Auth::id(); // Get the authenticated user ID

    if (!$userId) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // Fetch referral points set by admin
    $loyaltyPoint = LoyaltyPoints::first(); // Assuming there's a single entry

    if (!$loyaltyPoint) {
        return response()->json(['message' => 'No referral points set by admin'], 400);
    }

    $pointsEarned = $loyaltyPoint->on_referal; // Points admin assigned for referral

    // Store earned points in the user loyalty earnings table
    UserLoyaltyEarning::create([
        'user_id' => $userId,
        'earned_points' => $pointsEarned
    ]);

    return response()->json([
        'message' => 'Loyalty points added successfully',
        'earned_points' => $pointsEarned
    ]);
}


// public function getUserLoyaltyPoints()
// {
//     $userId = Auth::id(); // Get the logged-in user ID

//     if (!$userId) {
//         return response()->json([
//             'message' => 'User not authenticated',
//         ], 401);
//     }

//     // Fetch total earned points from user_loyalty_earnings table
//     $totalPoints = UserLoyaltyEarning::where('user_id', $userId)->sum('earned_points');

//     // Get the history of earned points (both referrals and bookings)
//     $loyaltyHistory = UserLoyaltyEarning::where('user_id', $userId)
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->map(function ($record) {
//             return [
//                 'source' => $record->source ?? 'Referral', // Indicates if the points are from a referral or a car booking
//                 'earned_points' => $record->earned_points,
//                 // 'date' => $record->created_at->format('Y-m-d H:i:s'),
//             ];
//         });

//     return response()->json([
//         'total_points' => $totalPoints,
//         'history' => $loyaltyHistory,
//     ]);
// }
// public function getUserLoyaltyPoints()
// {
//     $userId = Auth::id(); // Get the logged-in user ID

//     if (!$userId) {
//         return response()->json([
//             'message' => 'User not authenticated',
//         ], 401);
//     }

//     // Step 1: Get all car IDs booked or requested by the user
//     $bookings = Booking::with('car')->where('user_id', $userId)->get();
//     $requestBookings = RequestBooking::with('car')->where('user_id', $userId)->get();

//     // Merge car IDs to get unique ones
//     $carIds = $bookings->pluck('car.id')->merge($requestBookings->pluck('car.id'))->unique();

//     // Step 2: Get total loyalty points assigned for these cars
//     $totalCarPoints = LoyaltyPoints::with('car')->whereIn('car_id', $carIds)->sum('on_car');

//     // Step 3: Get referral-earned points
//     $totalReferralPoints = UserLoyaltyEarning::where('user_id', $userId)
//         // ->where('source', 'Referral') // Only get referral-based earnings
//         ->sum('earned_points');

//     // Step 4: Combine both totals (car booking + referral points)
//     $totalPoints = $totalCarPoints + $totalReferralPoints;

//     // Step 5: Get referral-only history
//     $referralLoyaltyHistory = UserLoyaltyEarning::where('user_id', $userId)
//         // ->where('source', 'Referral')
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->map(function ($record) {
//             return [
//                 'earned_points' => $record->earned_points,
//                 // 'date' => $record->created_at->format('Y-m-d H:i:s'),
//             ];
//         });

//     return response()->json([
//         'total_points' => $totalPoints, // Total from bookings + referrals
//         'history' => $referralLoyaltyHistory, // Only show referral earnings
//     ]);
// }
public function getUserLoyaltyPoints()
{
    $userId = Auth::id(); // Get the logged-in user ID

    if (!$userId) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    // Step 1: Get all car IDs booked or requested by the user
    $bookings = Booking::with('car')->where('user_id', $userId)->get();
    $requestBookings = RequestBooking::with('car')->where('user_id', $userId)->get();

    // Merge car IDs to get unique ones
    $carIds = $bookings->pluck('car.id')->merge($requestBookings->pluck('car.id'))->unique();

    // Step 2: Get total loyalty points assigned for these cars (on_car points)
    $totalCarPoints = LoyaltyPoints::whereIn('car_id', $carIds)->sum('on_car');

    // Step 3: Get referral-earned points from the loyalty_points table
    $totalReferralPoints = LoyaltyPoints::sum('on_referal'); // Fetch total referral points assigned

    // Step 4: Combine both totals (car booking + referral points)
    $totalPoints = $totalCarPoints + $totalReferralPoints;

    return response()->json([
        'total_points' => $totalPoints, // Total from bookings + referrals
    ]);
}

}
