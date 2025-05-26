<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use App\Models\LoyaltyRedemption;
use App\Models\UserLoyaltyEarning;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoyaltyPointController extends Controller
{
//    
public function getLoyaltyPoints()
{
    $userId = Auth::id(); // Get the logged-in user ID

    if (!$userId) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    // Fetch existing total points from UserLoyaltyEarning
    $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->orderBy('id', 'desc')->first();
    $currentTotalPoints = $userLoyalty ? $userLoyalty->total_points : 0;

    // Fetch all user bookings and request bookings
    $bookings = Booking::with('car')->where('user_id', $userId)->where('status', 1)->get();
    $requestBookings = RequestBooking::with('car')->where('user_id', $userId)->where('status', 1)->get();

    // Extract unique car IDs
    $carIds = $bookings->pluck('car.id')->merge($requestBookings->pluck('car.id'))->unique();

    // Fetch loyalty points for booked cars
    $loyaltyPoints = LoyaltyPoints::whereIn('car_id', $carIds)->get();

    // **DO NOT RECALCULATE OR ADD NEW POINTS HERE**  
    // This prevents admin updates from affecting total_points.
    $loyaltyHistory = UserLoyaltyEarning::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

    // Format response
    $data = $loyaltyHistory->map(function ($point) {
        return [
            'car_name' => $point->car_name ?? 'N/A',
            'earned_points' => $point->earned_points, // Show the current on_car value but don't update total_points
            'discount' => $point->discount,
        ];
    });

    return response()->json([
        'total_points' => $currentTotalPoints, // Show stored total_points, don't recalculate
        'history' => $data,
    ]);
}

// public function getLoyaltyPoints()
// {
//     $userId = Auth::id(); // Get the logged-in user ID

//     if (!$userId) {
//         return response()->json([
//             'message' => 'User not authenticated',
//         ], 401);
//     }

//     // Get all bookings and request bookings for the user
//     // $bookings = Booking::where('user_id', $userId)->pluck('car_id');
//     // $requestBookings = RequestBooking::where('user_id', $userId)->pluck('car_id');

//     // // Merge both car_ids (avoid duplicates)
//     // $carIds = $bookings->merge($requestBookings)->unique();
//     $bookings = Booking::with('car')->where('user_id', $userId)->get();
//     $requestBookings = RequestBooking::with('car')->where('user_id', $userId)->get();

//     // Extract unique car IDs
//     $carIds = $bookings->pluck('car.id')->merge($requestBookings->pluck('car.id'))->unique();

//     // Get loyalty points for these cars
//     $loyaltyPoints = LoyaltyPoints::with('car')->whereIn('car_id', $carIds)->get();

//     // Format response
//     $data = $loyaltyPoints->map(function ($point) {
//         return [
//             'car_name' => $point->car->car_name ?? 'N/A',
//             'on_car' => $point->on_car,
//             'discount' => $point->discount,
//         ];
//     });

//     return response()->json([
//         'total_points' => $loyaltyPoints->sum('on_car'),
//         'history' => $data,
//     ]);
// }

// public function earnLoyaltyPoints(Request $request)
// {
//     $userId = Auth::id(); // Get the authenticated user ID

//     if (!$userId) {
//         return response()->json(['message' => 'User not authenticated'], 401);
//     }

//     // Fetch referral points set by admin
//     $loyaltyPoint = LoyaltyPoints::first(); // Assuming there's a single entry

//     if (!$loyaltyPoint) {
//         return response()->json(['message' => 'No referral points set by admin'], 400);
//     }

//     $pointsEarned = $loyaltyPoint->on_referal; // Points admin assigned for referral

//     // Store earned points in the user loyalty earnings table
//     UserLoyaltyEarning::create([
//         'user_id' => $userId,
//         'earned_points' => $pointsEarned
//     ]);

//     return response()->json([
//         'message' => 'Loyalty points added successfully',
//         'earned_points' => $pointsEarned
//     ]);
// }
// public function earnLoyaltyPoints(Request $request)
// {
//     $userId = Auth::id(); // Get the authenticated user ID

//     if (!$userId) {
//         return response()->json(['message' => 'User not authenticated'], 401);
//     }

//     // Fetch referral points set by admin
//     $loyaltyPoint = LoyaltyPoints::where('id', 14)->first(); // Assuming there's a single entry

//     if (!$loyaltyPoint) {
//         return response()->json(['message' => 'No referral points set by admin'], 400);
//     }

//     $pointsEarned = $loyaltyPoint->on_referal; // Points admin assigned for referral

//     // Check if user already has a record in UserLoyaltyEarning
//     $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->first();

//     if ($userLoyalty) {
//         // Update total points
//         $userLoyalty->increment('total_points', $pointsEarned);
//     } else {
//         // Create new entry for the user
//         UserLoyaltyEarning::create([
//             'user_id' => $userId,
//             'total_points' => $pointsEarned // Store total points directly
//         ]);
//     }

//     return response()->json([
//         'message' => 'Loyalty points added successfully',
//         'earned_points' => $pointsEarned
//     ]);
// }

public function earnLoyaltyPoints(Request $request)
{
    $userId = Auth::id();

    if (!$userId) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $user = User::find($userId);

    // Generate referral code if not already present
    if (!$user->referral_code) {
        $user->referral_code = strtoupper(Str::random(8));
        $user->save();
    }

    // Create referral link
    $referralLink = url('/register?ref=' . $user->referral_code);

    return response()->json([
        'message' => 'Referral link generated successfully',
        'referral_code' => $user->referral_code,
        'referral_link' => $referralLink
    ]);
}


public function getUserLoyaltyPoints()
{
    $userId = Auth::id(); // Get the logged-in user ID

    if (!$userId) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    // Step 1: Get total points from the UserLoyaltyEarning table (which is updated after redemption)
    $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->first();
    $totalPoints = $userLoyalty ? $userLoyalty->total_points : 0;

    return response()->json([
        'total_points' => $totalPoints, // Show only the updated total points after redemption
    ]);
}

public function redeemLoyaltyPoints(Request $request)
{
   $userId = Auth::id();

    // Fetch the latest user loyalty data
    $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->orderBy('id', 'desc')->first();

    if (!$userLoyalty) {
        return response()->json([
            'status' => false,
            'message' => 'No loyalty points found.',
            'total_points' => 0,
            'redeemable_points' => 0,
        ], 200);
    }

    return response()->json([
        'status' => true,
        'message' => 'Loyalty points fetched successfully.',
        'total_points' => $userLoyalty->total_points,
        // 'redeemable_points' => $userLoyalty->total_points, // Can customize if business logic changes
    ], 200);
}

public function getRedemptionHistory()
{
    $userId = Auth::id();

    $history = LoyaltyRedemption::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get(['redeemed_points', 'created_at'])
        ->map(function ($record) {
            return [
                'redeemed_points' => $record->redeemed_points,
                'date' => $record->created_at->format('d M Y'),
            ];
        });

    return response()->json(['history' => $history]);
}

public function getPoints()
{
    $carIds = DB::table('car_details')->pluck('id'); // get car IDs from car_details

    $loyaltyPoints = LoyaltyPoints::with('car') // only fetch 'car_id' and 'id'
    ->whereIn('car_id', $carIds)
    ->select('car_id', 'on_car', 'discount')
    ->get()
    ->map(function ($item) {
        return [
            'car_id' => $item->car ? $item->car->car_id : null, 
            'car_name' => $item->car ? $item->car->car_name : null,
            'on_car' => $item->on_car,
            'discount' => $item->discount,
        ];
    });
    return response()->json([
        'message' => 'Loyalty points fetched successfully',
        'data' => $loyaltyPoints
    ]);
}

public function lastLoyaltyTransaction(Request $request)
{
    $userId = Auth::user();

    $booking = Booking::where('user_id', $userId)
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->first();

    $requestBooking = RequestBooking::where('user_id', $userId)
                        ->where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->first();

    $latest = null;
    if ($booking && $requestBooking) {
        $latest = $booking->created_at > $requestBooking->created_at ? $booking : $requestBooking;
    } elseif ($booking) {
        $latest = $booking;
    } elseif ($requestBooking) {
        $latest = $requestBooking;
    }

    if (!$latest) {
        return response()->json([
            'message' => 'No completed transactions found.',
            'total_purchase' => 0,
            'earned_points' => 0,
        ]);
    }

    $carId = $latest->car_id;
    $price = $latest->price;

    $points = DB::table('loyalty_points')
                ->where('car_id', $carId)
                ->value('on_car') ?? 0;

    return response()->json([
        'total_purchase' => $price,
        'earned_points' => $points,
    ]);
}


}
