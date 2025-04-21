<?php

namespace App\Http\Controllers\API;

use DB;
use Log;
use Carbon\Carbon;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\CarDetails;
use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use App\Models\AssignedRequest;
use App\Models\DriverNotification;
use App\Models\UserLoyaltyEarning;
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
            'price' => $request->price,
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
        $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->orderBy('id', 'desc')->first();
        $totalPoints = $userLoyalty ? $userLoyalty->total_points + $loyaltyPoints->on_car : $loyaltyPoints->on_car;

        // Create a new loyalty record for each booking
        Log::info("Creating new loyalty entry for user with points: " . $loyaltyPoints->on_car);
        UserLoyaltyEarning::create([
            'user_id'       => $userId,
            'total_points'  => $totalPoints, // Keep adding to total points
            'earned_points' => $loyaltyPoints->on_car, // Earned points for this booking
            'car_name'      => $carDetails->car_name,
            // 'on_car'        => $loyaltyPoints->on_car,
            'discount'      => $loyaltyPoints->discount
        ]);
        Log::info("Previous total: " . ($userLoyalty->total_points ?? 0));
        Log::info("New on_car points: " . $loyaltyPoints->on_car);
        Log::info("Calculated total_points: " . $totalPoints);
        
        Log::info("New loyalty entry created successfully.");   
    } else {
        Log::info("No Loyalty Points found for car_id: {$carDetails->id}");
    }
} else {
    Log::info("No car found in car_details for car_id: " . $request->car_id);
}

        return response()->json([
            // 'status' => true,
            'message' => 'Booking created successfully.',
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
            'price' => $request->price,
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
        $totalPoints = $userLoyalty ? $userLoyalty->total_points + $loyaltyPoints->on_car : $loyaltyPoints->on_car;

        // Create a new loyalty record for each booking
        Log::info("Creating new loyalty entry for user with points: " . $loyaltyPoints->on_car);
        UserLoyaltyEarning::create([
            'user_id'       => $userId,
            'total_points'  => $totalPoints, // Keep adding to total points
            'earned_points' => $loyaltyPoints->on_car, // Earned points for this booking
            'car_name'      => $carDetails->car_name,
            // 'on_car'        => $loyaltyPoints->on_car,
            'discount'      => $loyaltyPoints->discount
        ]);
        Log::info("Previous total: " . ($userLoyalty->total_points ?? 0));
        Log::info("New on_car points: " . $loyaltyPoints->on_car);
        Log::info("Calculated total_points: " . $totalPoints);
        
        Log::info("New loyalty entry created successfully.");  
    } else {
        Log::info("No Loyalty Points found for car_id: {$carDetails->id}");
    }
} else {
    Log::info("No car found in car_details for car_id: " . $request->car_id);
}

        return response()->json([
            // 'status' => true,
            'message' => 'Booking request created successfully.',
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

public function getDriverBookings(Request $request)
{
    $driverId = Auth::id(); // Get authenticated driver ID

    // Fetch pending bookings for the driver (status = 3)
    

    // $requestBookings = RequestBooking::where('status', 0)
    //                                  ->where('driver_id', $driverId)
    //                                  ->orwhere('dropoff_driver_id', $driverId)
    //                                  ->select('car_id','full_name', 'pickup_address', 'dropoff_address', 'pickup_date', 'pickup_time','dropoff_date','dropoff_time')
    //                                  ->with(['car' => function ($query) {
    //                                     $query->select('car_id', 'pricing', 'sanitized', 'car_feature'); // Ensure 'id' is included for relationship mapping
    //                                 }])
    //                                 ->whereNotNull('car_id')
    //                                  ->get();

    //                                  $requestBookings->transform(function ($booking) {
    //                                     $pickupDate = Carbon::parse($booking->pickup_date);
    //                                     $dropoffDate = Carbon::parse($booking->dropoff_date);
    //                                     $booking->total_days = $dropoffDate->diffInDays($pickupDate) + 1; // Ensure it includes the pickup day
    //                                     return $booking;
    //                                 });
    //                                 \Log::info($requestBookings);

    $pickupRequests = RequestBooking::whereHas('assign', function ($query) use ($driverId) {
        $query->where('status', 0)
              ->where('driver_id', $driverId);
    })
    ->with(['assign' => function ($query) use ($driverId) {
        $query->where('status', 0)->where('driver_id', $driverId);
    }, 'car' => function ($query) {
        $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
    }])
    ->get()
    ->map(function ($booking) {
        return [
            'assigned_id' => optional($booking->assign->first())->id,
            'id' => $booking->id,
            'car_id' => $booking->car_id,
            'full_name' => $booking->full_name,
            'pickup_address' => $booking->pickup_address,
            // 'dropoff_address' => $booking->dropoff_address,
            'pickup_date' => $booking->pickup_date,
            'pickup_time' => $booking->pickup_time,
            // 'dropoff_date' => $booking->dropoff_date,
            // 'dropoff_time' => $booking->dropoff_time,
            'car' => $booking->car,
        ];
    });

// Dropoff Requests
$dropoffRequests = RequestBooking::whereHas('assign', function ($query) use ($driverId) {
        $query->where('status', 0)
              ->where('dropoff_driver_id', $driverId);
    })
    ->with(['assign' => function ($query) use ($driverId) {
        $query->where('status', 0)->where('dropoff_driver_id', $driverId);
    }, 'car' => function ($query) {
        $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
    }])
    ->get()
    ->map(function ($booking) {
        return [
            'assigned_id' => optional($booking->assign->first())->id,
            'id' => $booking->id,
            'car_id' => $booking->car_id,
            'full_name' => $booking->full_name,
            // 'pickup_address' => $booking->pickup_address,
            'dropoff_address' => $booking->dropoff_address,
            // 'pickup_date' => $booking->pickup_date,
            // 'pickup_time' => $booking->pickup_time,
            'dropoff_date' => $booking->dropoff_date,
            'dropoff_time' => $booking->dropoff_time,
            'car' => $booking->car,
        ];
    });

    return response()->json([
        // 'current_bookings' => $requestBookings
        'pickup_requests' => $pickupRequests,
        'dropoff_requests' => $dropoffRequests
    ], 200);
}

public function getDriverBookingRequests(Request $request)
{
    $driverId = Auth::id();

    $pickupRequests = RequestBooking::whereHas('assign', function ($query) use ($driverId) {
        $query->where('status', 3)
              ->where('driver_id', $driverId);
    })
    ->with(['assign' => function ($query) use ($driverId) {
        $query->where('status', 3)->where('driver_id', $driverId);
    }, 'car' => function ($query) {
        $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
    }])
    ->get()
    ->map(function ($booking) {
        return [
            'assigned_id' => optional($booking->assign->first())->id,
            'id' => $booking->id,
            'car_id' => $booking->car_id,
            'full_name' => $booking->full_name,
            'pickup_address' => $booking->pickup_address,
            // 'dropoff_address' => $booking->dropoff_address,
            'pickup_date' => $booking->pickup_date,
            'pickup_time' => $booking->pickup_time,
            // 'dropoff_date' => $booking->dropoff_date,
            // 'dropoff_time' => $booking->dropoff_time,
            'car' => $booking->car,
        ];
    });

// Dropoff Requests
$dropoffRequests = RequestBooking::whereHas('assign', function ($query) use ($driverId) {
        $query->where('status', 3)
              ->where('dropoff_driver_id', $driverId);
    })
    ->with(['assign' => function ($query) use ($driverId) {
        $query->where('status', 3)->where('dropoff_driver_id', $driverId);
    }, 'car' => function ($query) {
        $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
    }])
    ->get()
    ->map(function ($booking) {
        return [
            'assigned_id' => optional($booking->assign->first())->id,
            'id' => $booking->id,
            'car_id' => $booking->car_id,
            'full_name' => $booking->full_name,
            // 'pickup_address' => $booking->pickup_address,
            'dropoff_address' => $booking->dropoff_address,
            // 'pickup_date' => $booking->pickup_date,
            // 'pickup_time' => $booking->pickup_time,
            'dropoff_date' => $booking->dropoff_date,
            'dropoff_time' => $booking->dropoff_time,
            'car' => $booking->car,
        ];
    });


return response()->json([
    'message' => 'Pending booking requests fetched successfully',
    // 'booking_requests' => $requests
    'pickup_requests' => $pickupRequests,
        'dropoff_requests' => $dropoffRequests
], 200);
}

public function updateBookingStatus(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
        'assigned_id' => 'required|integer',
        'status' => 'required|in:0,2', // 0 = Active, 2 = Rejected
    ]);
    $driverId = Auth::id();
    $assignedId = $request->assigned_id;
    $requestBooking = RequestBooking::find($request->id);

    if (!$requestBooking) {
        return response()->json(['message' => 'Booking not found'], 404);
    }

    // Update the status based on driver's action
    $requestBooking->status = $request->status;
    $driver = Driver::find($driverId);
    $assignedRequest = AssignedRequest::where('id', $assignedId)->first();
    // If accepted, mark driver as unavailable
    if ($request->status == 0) {
        // $driver = RequestBooking::find($requestBooking->driver_id);
        DB::table('assigned_requests')
        ->where('id', $assignedId)
        ->update(['status' => 0]);

        if ($assignedRequest->driver_id == $driverId) {
            $requestBooking->driver_id = $driverId;
            \Log::info("Pickup driver accepted, driver_id set to {$driverId}.");
        }

        if ($assignedRequest->dropoff_driver_id == $driverId) {
            $requestBooking->dropoff_driver_id = $driverId;
            \Log::info("Dropoff driver accepted, dropoff_driver_id set to {$driverId}.");
        }
        
        $assignedDrivers = AssignedRequest::where('request_booking_id', $requestBooking->id)
    ->where('status', 0) // status 0 means accepted
    ->get();

$pickupAccepted = false;
$dropoffAccepted = false;

foreach ($assignedDrivers as $assigned) {
    if ($assigned->driver_id !== null) {
        $pickupAccepted = true;
    }
    if ($assigned->dropoff_driver_id !== null) {
        $dropoffAccepted = true;
    }
}

// Now set the booking status based on both drivers' acceptance
if ($pickupAccepted && $dropoffAccepted) {
    $requestBooking->status = 0; // Either driver accepted
    \Log::info("Both pickup and dropoff drivers accepted. Booking status set to 0.");
} else {
    $requestBooking->status = 3; // Waiting for the other driver
    \Log::info("One driver accepted. Waiting for the other driver.");
}
        
        $requestBooking->save();

        if ($driver) {
            $driver->is_available = 0;
            $driver->save();
        }
        $roles = [];

        if ($assignedRequest->driver_id == $driverId) {
            $roles[] = 'Pickup Driver';
        }
        if ($assignedRequest->dropoff_driver_id == $driverId) {
            $roles[] = 'Dropoff Driver';
        }
        
        $roleText = implode(' & ', $roles);

        DriverNotification::create([
            'driver_id' => $driverId,
            'type' => 'booking',
            'message' => "{$driver->name} ({$roleText}) has accepted booking request.",
            'is_read' => 0,
        ]);
    }
    
    elseif ($request->status == 2) {
        // $rejectionRole = $request->input('rejection_role');

        DB::table('assigned_requests')
        ->where('id', $assignedId)
        ->delete();

    // Nullify the driver_id or dropoff_driver_id
    if ($assignedRequest->driver_id == $driverId) {
        $requestBooking->driver_id = null;
        \Log::info("Pickup driver rejected, assignment deleted and driver_id set to null.");
    }

    if ($assignedRequest->dropoff_driver_id == $driverId) {
        $requestBooking->dropoff_driver_id = null;
        \Log::info("Dropoff driver rejected, assignment deleted and dropoff_driver_id set to null.");
    }
        
        if (is_null($requestBooking->driver_id) && is_null($requestBooking->dropoff_driver_id)) {
            $requestBooking->status = 2;
            Log::info("Both roles rejected, setting status to 2 (rejected)");
        }else {
            // 👇 Prevent premature status override
            $requestBooking->status = 3; // or whatever "requested" or "partial" is
        }
        // $driver = Driver::find($driverId);
        if ($driver) {
            $driver->is_available = 1;
            $driver->save();
        }

        $requestBooking->save();
        //  $driver = RequestBooking::find($requestBooking->driver_id);
        $roles = [];

        if ($assignedRequest->driver_id == $driverId) {
            $roles[] = 'Pickup Driver';
        }
        if ($assignedRequest->dropoff_driver_id == $driverId) {
            $roles[] = 'Dropoff Driver';
        }
        
        $roleText = implode(' & ', $roles);

        DriverNotification::create([
            'driver_id' => $driverId,
            'type' => 'booking',
            'message' => "{$driver->name} ({$roleText}) has rejected booking request.",
            'is_read' => 0,
        ]);
    }

    return response()->json(['message' => 'Booking status updated successfully'], 200);
}

public function DriverBookingHistory(Request $request)
{
    $driverId = Auth::id(); // Get authenticated driver ID

    // Fetch pending bookings for the driver (status = 3)
    

    // $requestBookings = RequestBooking::where('status', 1)
    //                                  ->where('driver_id', $driverId)
    //                                  ->orwhere('dropoff_driver_id', $driverId)
    //                                  ->select('car_id','full_name', 'pickup_address', 'dropoff_address', 'pickup_date', 'pickup_time','dropoff_date','dropoff_time')
    //                                  ->with(['car' => function ($query) {
    //                                     $query->select('car_id', 'pricing', 'sanitized', 'car_feature'); // Ensure 'id' is included for relationship mapping
    //                                 }])
    //                                 ->whereNotNull('car_id')
    //                                  ->get();

    $pickupRequests = RequestBooking::whereHas('assign', function ($query) use ($driverId) {
        $query->where('status', 1)
              ->where('driver_id', $driverId);
    })
    ->with(['assign' => function ($query) use ($driverId) {
        $query->where('status', 1)->where('driver_id', $driverId);
    }, 'car' => function ($query) {
        $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
    }])
    ->get()
    ->map(function ($booking) {
        return [
            'assigned_id' => optional($booking->assign->first())->id,
            'id' => $booking->id,
            'car_id' => $booking->car_id,
            'full_name' => $booking->full_name,
            'pickup_address' => $booking->pickup_address,
            // 'dropoff_address' => $booking->dropoff_address,
            'pickup_date' => $booking->pickup_date,
            'pickup_time' => $booking->pickup_time,
            // 'dropoff_date' => $booking->dropoff_date,
            // 'dropoff_time' => $booking->dropoff_time,
            'car' => $booking->car,
        ];
    });

// Dropoff Requests
$dropoffRequests = RequestBooking::whereHas('assign', function ($query) use ($driverId) {
        $query->where('status', 1)
              ->where('dropoff_driver_id', $driverId);
    })
    ->with(['assign' => function ($query) use ($driverId) {
        $query->where('status', 1)->where('dropoff_driver_id', $driverId);
    }, 'car' => function ($query) {
        $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
    }])
    ->get()
    ->map(function ($booking) {
        return [
            'assigned_id' => optional($booking->assign->first())->id,
            'id' => $booking->id,
            'car_id' => $booking->car_id,
            'full_name' => $booking->full_name,
            // 'pickup_address' => $booking->pickup_address,
            'dropoff_address' => $booking->dropoff_address,
            // 'pickup_date' => $booking->pickup_date,
            // 'pickup_time' => $booking->pickup_time,
            'dropoff_date' => $booking->dropoff_date,
            'dropoff_time' => $booking->dropoff_time,
            'car' => $booking->car,
        ];
    });
    return response()->json([
        // 'booking_history' => $requestBookings
        'pickup_requests' => $pickupRequests,
        'dropoff_requests' => $dropoffRequests
    ], 200);
}
}
// public function getDriverBookingRequests(Request $request)
// {
//     $driverId = Auth::id();

//     $requests = RequestBooking::where(function ($query) use ($driverId) {
//     //     $query->where('driver_id', $driverId)
//     //           ->orWhere('dropoff_driver_id', $driverId);
//     // })
//     // ->where('status', 3) // Requested
//     $query->where(function ($q) use ($driverId) {
//         // Case: Driver assigned for both pickup and dropoff
//         $q->where('driver_id', $driverId)
//           ->where('dropoff_driver_id', $driverId);
//     })
//     ->orWhere(function ($q) use ($driverId) {
//         // Case: Driver assigned only for pickup
//         $q->where('driver_id', $driverId)
//         ->where(function ($innerQ) use ($driverId) {
//             $innerQ->whereNull('dropoff_driver_id')->orWhere('dropoff_driver_id', '!=', $driverId);
//         });
//     })
//     ->orWhere(function ($q) use ($driverId) {
//         // Case: Driver assigned only for dropoff
//         $q->where('dropoff_driver_id', $driverId)
//         ->where(function ($innerQ) use ($driverId) {
//             $innerQ->whereNull('driver_id')->orWhere('driver_id', '!=', $driverId);
//         });
//     });
// })
// ->where(function ($q) {
//     // Only show bookings that are not fully accepted/rejected
//     $q->where('status', 3); // You might enhance this if you track part-by-part status
// })
//     ->select(
//         'id',
//         'car_id',
//         'full_name',
//         'pickup_address',
//         'dropoff_address',
//         'pickup_date',
//         'pickup_time',
//         'dropoff_date',
//         'dropoff_time'
//     )
//     ->with(['car' => function ($query) {
//         $query->select('car_id', 'pricing', 'sanitized', 'car_feature');
//     }])
//     ->get();

// return response()->json([
//     'message' => 'Pending booking requests fetched successfully',
//     'booking_requests' => $requests
// ], 200);
// }
