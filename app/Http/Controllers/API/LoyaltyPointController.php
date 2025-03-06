<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoyaltyPointController extends Controller
{
    public function getLoyaltyPoints()
{
    $userId = Auth::id(); // Get the logged-in user ID

    if (!$userId) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    // Join loyalty_points → bookings → car_inventory
    $loyaltyPoints = LoyaltyPoints::with('booking.car')
    ->whereHas('booking', function ($query) use ($userId) {
        $query->where('user_id', $userId);
    })->get();

$data = $loyaltyPoints->map(function ($point) {
    return [
        'car_name' => $point->booking->car->car_name ?? 'N/A',
        'on_car' => $point->on_car,
        'discount' => $point->discount,
    ];
});

return response()->json([
    'total_points' => $loyaltyPoints->sum('earned_points'),
    'history' => $data,
]);

}

}
