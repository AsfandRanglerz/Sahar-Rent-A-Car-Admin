<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\User;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\CarDetails;
use App\Models\SubAdminLog;
use Illuminate\Http\Request;
use App\Jobs\NotificationJob;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use App\Models\AssignedRequest;
use App\Models\LicenseApproval;
use App\Models\UserLoyaltyEarning;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RequestBookingController extends Controller
{
    public function pendingCounter(){
        $orderCount = RequestBooking::
        Where('driver_id', null)
        ->whereNotNull('pickup_address')
        ->count();
         return response()->json(['count' => $orderCount]);
    }

    public function index(Request $request)
    {
        
        // // $bookings = Booking::latest()->get();
        // $requestbookings = RequestBooking::with('assign')
        // ->whereNotNull('pickup_address')
        // ->whereIn('status', [0, 2, 3, 1])
        // // ->orderBy('status','ASC')
        // ->latest()
        // ->get();
        
        // $drivers = Driver::all();
        $status = $request->get('status');

        // $query = RequestBooking::with('assign')->whereNotNull('pickup_address');
        $query = RequestBooking::with('assign')
        ->whereNotNull('pickup_address')
        ->where('status', '!=', 1) // <-- Exclude bookings with status = 1
        ->whereDoesntHave('assign', function ($q) {
            $q->where('status', 1)
              ->whereNotNull('driver_id')
              ->whereNotNull('dropoff_driver_id');
        });
    
        if ($status) {
            $query = $query->where(function ($q) use ($status) {
                if ($status === 'Completed') {
                    $q->whereHas('assign', function ($subQ) {
                        $subQ->where('status', 1);
                    });
                } elseif ($status === 'Active') {
                    $q->where(function ($subQ) {
                        $subQ->where('status', 0)
                             ->orWhereHas('assign', function ($a) {
                                 $a->where('status', 0);
                             });
                    });
                } elseif ($status === 'Pending') {
                    $q->where('status', 2)
                      ->orWhere(function ($subQ) {
                          $subQ->where('status', 3)->whereDoesntHave('assign')
                              ->orWhereHas('assign', function ($a) {
                                  $a->where('status', 3)->whereNull('driver_id');
                              });
                      });
                } elseif ($status === 'Requested') {
                    $q->whereHas('assign', function ($a) {
                        $a->where('status', 3)->whereNotNull('driver_id');
                    });
                }
            });
        }
    
        $requestbookings = $query->whereIn('status', [0, 2, 3, 1])->latest()->get();
        $drivers = Driver::all();
        return view('admin.RequestBooking.index',compact('requestbookings','drivers'));
    }

public function edit(Request $request, $id)
{
     
$requestBooking = RequestBooking::with('assign')->findOrFail($id);

if ($request->self_pickup === 'No') {
    $driver = Driver::where('id', $request->driver_id)
    ->where('is_available', 1) // Ensuring only available drivers are selected
    ->first();                                                                      //find($request->driver_id);

    // Validate if the driver exists
    if (!$driver) {
        return response()->json(['message' => 'Driver not found.'], 404);
    }

    // Check if driver's license is approved
    $licenseApproval = LicenseApproval::where('driver_id', $request->driver_id)->first();
    if (!$licenseApproval || $licenseApproval->status == '0' || $licenseApproval->status == '2') {
        $statusText = $licenseApproval ? ($licenseApproval->status == '0' ? 'Rejected' : 'Pending') : 'Not Submitted';
        return response()->json([
            'message' => 'Driver license not approved yet.'
        ], 400);
    }

   
     
    
$isDriverAssigned = RequestBooking::where('driver_id', $request->driver_id)
    ->where('status', '!=', 1) // Exclude completed bookings
    ->where('status', '!=', 2) // Exclude Pending bookings
    ->where('id', '!=', $requestBooking->id) // Prevent checking the same booking
    ->where(function ($query) use ($requestBooking) {
        // ✅ Case 1: Only Pickup Date & Pickup Time Exist (dropoff_date & dropoff_time are NULL)
        if (!empty($requestBooking->pickup_date) && !empty($requestBooking->pickup_time) && empty($requestBooking->dropoff_date) && empty($requestBooking->dropoff_time)) {
            $query->where(function ($q) use ($requestBooking) {
                // Case 1.1: Overlap with another booking's Dropoff Date & Dropoff Time
                $q->where('dropoff_date', $requestBooking->pickup_date) // Another booking's dropoff_date matches this pickup_date
                  ->whereNotNull('dropoff_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('dropoff_time', '>', $requestBooking->pickup_time)
                                ->orWhere('dropoff_time', '=', $requestBooking->pickup_time);
                  });
            })->orWhere(function ($q) use ($requestBooking) {
                // Case 1.2: Overlap with another booking's Pickup Date & Pickup Time
                $q->where('pickup_date', $requestBooking->pickup_date) // Matching pickup_date
                  ->whereNotNull('pickup_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('pickup_time', '<=', $requestBooking->pickup_time) // Overlaps with an earlier pickup time
                                ->where(function ($subQuery) use ($requestBooking) {
                                    $subQuery->whereNull('dropoff_time') // No dropoff time means it's still ongoing
                                             ->orWhere('dropoff_time', '>', $requestBooking->pickup_time); // Still active at that time
                                });
                  });
            });
        }

        // ✅ Case 2: Both Pickup and Dropoff Dates Exist
        elseif (!empty($requestBooking->pickup_date) && !empty($requestBooking->dropoff_date)) {
            $query->where(function ($q) use ($requestBooking) {
                $q->whereBetween('pickup_date', [$requestBooking->pickup_date, $requestBooking->dropoff_date])
                  ->orWhereBetween('dropoff_date', [$requestBooking->pickup_date, $requestBooking->dropoff_date])
                  ->orWhere(function ($subQuery) use ($requestBooking) {
                      $subQuery->where('pickup_date', '<', $requestBooking->pickup_date)
                               ->where('dropoff_date', '>', $requestBooking->dropoff_date);
                  });
            });

            // Time Check (Only if both pickup_time and dropoff_time exist)
            if (!empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_time)) {
                $query->where(function ($q) use ($requestBooking) {
                    $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
                      ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
                      ->orWhere(function ($subQuery) use ($requestBooking) {
                          $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
                                   ->where('dropoff_time', '>', $requestBooking->dropoff_time);
                      });
                });
            }
        }

        // ✅ Case 3: Only Dropoff Date & Dropoff Time Exist (pickup_date & pickup_time are NULL)
        elseif (empty($requestBooking->pickup_date) && empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_date) && !empty($requestBooking->dropoff_time)) {
            $query->where(function ($q) use ($requestBooking) {
                // Case 3.1: Overlap with another booking’s Pickup Date & Pickup Time
                $q->where('pickup_date', $requestBooking->dropoff_date) // Another booking's pickup_date matches this dropoff_date
                  ->whereNotNull('pickup_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('pickup_time', '<', $requestBooking->dropoff_time) // Pickup time is before dropoff time
                                ->orWhere('pickup_time', '=', $requestBooking->dropoff_time); // Exact match
                  });
            })->orWhere(function ($q) use ($requestBooking) {
                // Case 3.2: Overlap with another booking’s Dropoff Date & Dropoff Time
                $q->where('dropoff_date', $requestBooking->dropoff_date) // Matching dropoff_date
                  ->whereNotNull('dropoff_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('dropoff_time', '>', $requestBooking->dropoff_time) // Dropoff time is after the current one
                                ->orWhere('dropoff_time', '=', $requestBooking->dropoff_time);
                  });
            });
        }
    })
    ->exists();




if ($isDriverAssigned) {
    return response()->json([
        'message' => 'This driver is already assigned for another booking during this time.'
    ], 400);
}

$requestBooking->driver_id = $request->driver_id;
// $driver->is_available = 0;
// $driver->save();
if ($driver && $driver->fcm_token) {
    dispatch(new NotificationJob(
        $driver->fcm_token,
        'New Dropoff Request Assigned',
        'A new dropoff request has been assigned to you.',
        [
            'request_booking_id' => $requestBooking->id,
            'type' => 'dropoff'
        ]
    ));
}

Notification::create([
    'driver_id'    => $driver->id,
    'user_type'  => 'driver',
    'title'      => 'New Dropoff Request Assigned',
    'description'    => 'A new dropoff request has been assigned to you.',
]);

}
    // Generate a unique 4-digit car_id if it doesn't exist
    // if (!$requestBooking->car_id) {
    //     do {
    //         $carId = mt_rand(1000, 9999);
    //     } while (RequestBooking::where('car_id', $carId)->exists());

    //     $requestBooking->car_id = $carId;
    // }

    if ($request->self_dropoff === 'No') {
        $dropoffDriver = Driver::where('id', $request->dropoff_driver_id)
            ->where('is_available', 1)
            ->first();

        if (!$dropoffDriver) {
            return response()->json(['message' => 'Pickup driver not found or unavailable.'], 404);
        }

        // Check if dropoff driver's license is approved
        $dropoffLicenseApproval = LicenseApproval::where('driver_id', $request->dropoff_driver_id)->first();
        if (!$dropoffLicenseApproval || $dropoffLicenseApproval->status == '0' || $dropoffLicenseApproval->status == '2') {
            return response()->json([
                'message' => 'Driver license not approved yet.'
            ], 400);
        }

    $isDropoffDriverAssigned = RequestBooking::where('dropoff_driver_id', $request->dropoff_driver_id)
    ->where('status', '!=', 1) // Exclude completed bookings
    ->where('status', '!=', 2) // Exclude Pending bookings
    ->where('id', '!=', $requestBooking->id) // Prevent checking the same booking
    ->where(function ($query) use ($requestBooking) {
        // ✅ Case 1: Only Pickup Date & Pickup Time Exist (dropoff_date & dropoff_time are NULL)
        if (!empty($requestBooking->pickup_date) && !empty($requestBooking->pickup_time) && empty($requestBooking->dropoff_date) && empty($requestBooking->dropoff_time)) {
            $query->where(function ($q) use ($requestBooking) {
                // Case 1.1: Overlap with another booking's Dropoff Date & Dropoff Time
                $q->where('dropoff_date', $requestBooking->pickup_date) // Another booking's dropoff_date matches this pickup_date
                  ->whereNotNull('dropoff_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('dropoff_time', '>', $requestBooking->pickup_time)
                                ->orWhere('dropoff_time', '=', $requestBooking->pickup_time);
                  });
            })->orWhere(function ($q) use ($requestBooking) {
                // Case 1.2: Overlap with another booking's Pickup Date & Pickup Time
                $q->where('pickup_date', $requestBooking->pickup_date) // Matching pickup_date
                  ->whereNotNull('pickup_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('pickup_time', '<=', $requestBooking->pickup_time) // Overlaps with an earlier pickup time
                                ->where(function ($subQuery) use ($requestBooking) {
                                    $subQuery->whereNull('dropoff_time') // No dropoff time means it's still ongoing
                                             ->orWhere('dropoff_time', '>', $requestBooking->pickup_time); // Still active at that time
                                });
                  });
            });
        }

        // ✅ Case 2: Both Pickup and Dropoff Dates Exist
        elseif (!empty($requestBooking->pickup_date) && !empty($requestBooking->dropoff_date)) {
            $query->where(function ($q) use ($requestBooking) {
                $q->whereBetween('pickup_date', [$requestBooking->pickup_date, $requestBooking->dropoff_date])
                  ->orWhereBetween('dropoff_date', [$requestBooking->pickup_date, $requestBooking->dropoff_date])
                  ->orWhere(function ($subQuery) use ($requestBooking) {
                      $subQuery->where('pickup_date', '<', $requestBooking->pickup_date)
                               ->where('dropoff_date', '>', $requestBooking->dropoff_date);
                  });
            });

            // Time Check (Only if both pickup_time and dropoff_time exist)
            if (!empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_time)) {
                $query->where(function ($q) use ($requestBooking) {
                    $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
                      ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
                      ->orWhere(function ($subQuery) use ($requestBooking) {
                          $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
                                   ->where('dropoff_time', '>', $requestBooking->dropoff_time);
                      });
                });
            }
        }

        // ✅ Case 3: Only Dropoff Date & Dropoff Time Exist (pickup_date & pickup_time are NULL)
        elseif (empty($requestBooking->pickup_date) && empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_date) && !empty($requestBooking->dropoff_time)) {
            $query->where(function ($q) use ($requestBooking) {
                // Case 3.1: Overlap with another booking’s Pickup Date & Pickup Time
                $q->where('pickup_date', $requestBooking->dropoff_date) // Another booking's pickup_date matches this dropoff_date
                  ->whereNotNull('pickup_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('pickup_time', '<', $requestBooking->dropoff_time) // Pickup time is before dropoff time
                                ->orWhere('pickup_time', '=', $requestBooking->dropoff_time); // Exact match
                  });
            })->orWhere(function ($q) use ($requestBooking) {
                // Case 3.2: Overlap with another booking’s Dropoff Date & Dropoff Time
                $q->where('dropoff_date', $requestBooking->dropoff_date) // Matching dropoff_date
                  ->whereNotNull('dropoff_date')
                  ->where(function ($timeQuery) use ($requestBooking) {
                      $timeQuery->where('dropoff_time', '>', $requestBooking->dropoff_time) // Dropoff time is after the current one
                                ->orWhere('dropoff_time', '=', $requestBooking->dropoff_time);
                  });
            });
        }
    })
    ->exists();




if ($isDropoffDriverAssigned) {
    return response()->json([
        'message' => 'This driver is already assigned for another booking during this time.'
    ], 400);
}

$requestBooking->dropoff_driver_id = $request->dropoff_driver_id;
        // $dropoffDriver->is_available = 0;
        // $dropoffDriver->save();

        if ($dropoffDriver && $dropoffDriver->fcm_token) {
    dispatch(new NotificationJob(
        $dropoffDriver->fcm_token,
        'New Pickup Request Assigned',
        'A new pickup request has been assigned to you.',
        [
            'request_booking_id' => $requestBooking->id,
            'type' => 'pickup'
        ]
    ));
}

Notification::create([
    'driver_id'    => $dropoffDriver->id,
    'user_type'  => 'driver',
    'title'      => 'New Pickup Request Assigned',
    'description'    => 'A new pickup request has been assigned to you.',
]);

    }

    if ($request->car_id) {
        $requestBooking->car_id = $request->car_id;
    }

    // $requestBooking->driver_id = $request->driver_id;
    
    $requestBooking->status = 3; //  '3' means requested
    $requestBooking->save();
    

    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;
    if ($request->self_pickup === 'No' && isset($request->driver_id)) {
        $pickupDriver = Driver::find($request->driver_id);
        SubAdminLog::create([
            'subadmin_id' => $subadmin->id,
            'section' => 'Dropoff Requests',
            'action' => 'Assign Driver',
            'message' => "SubAdmin {$subadminName} assigned Dropoff Driver {$pickupDriver->name}  with Car ID {$requestBooking->car_id}",
        ]);
    }

    if ($request->self_dropoff === 'No' && isset($request->dropoff_driver_id)) {
        $dropoffDriver = Driver::find($request->dropoff_driver_id);
        SubAdminLog::create([
            'subadmin_id' => $subadmin->id,
            'section' => 'Pickup Requests',
            'action' => 'Assign Driver',
            'message' => "SubAdmin {$subadminName} assigned Pickup Driver {$dropoffDriver->name}  with Car ID {$requestBooking->car_id}",
        ]);
    }
    }

    if ($request->self_pickup === 'No') {
        AssignedRequest::updateOrCreate(
            [
                'request_booking_id' => $requestBooking->id,
                'driver_id' => $request->driver_id,
                'dropoff_driver_id' => null,
            ],
            [
                // 'booking_id' => $requestBooking->booking_id,
                'status' => 3
            ]
        );
    }
    
    if ($request->self_dropoff === 'No') {
        AssignedRequest::updateOrCreate(
            [
                'request_booking_id' => $requestBooking->id,
                'driver_id' => null,
                'dropoff_driver_id' => $request->dropoff_driver_id,
            ],
            [
                // 'booking_id' => $requestBooking->booking_id,
                'status' => 3
            ]
        );
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Driver assigned successfully!',
        'driver_name' => $driver->driver_name ?? null,
        'dropoff_driver_name' => $dropoffDriver->driver_name ?? null,
        'car_id' => $requestBooking->car_id
    ]);
    
    }


public function markCompleted($id)
{
    $requestBooking = RequestBooking::with('assign')->findOrFail($id);
    $selfPickup = $requestBooking->self_pickup;
    $selfDropoff = $requestBooking->self_dropoff;

    // 1. Update only pickup driver status if both pickup and dropoff are required
    if ($selfPickup === 'No' && $selfDropoff === 'No') {
        foreach ($requestBooking->assign as $assigned) {
            if (!is_null($assigned->driver_id) && $assigned->status != 1) {
                $assigned->status = 1; // Mark as completed
                $assigned->save();

                Driver::where('id', $assigned->driver_id)->update(['is_available' => 1]);
            }
        }

        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;

            SubAdminLog::create([
                'subadmin_id' => $subadmin->id,
                'section' => 'Dropoff Requests',
                'action' => 'Update Dropoff Status',
                'message' => "SubAdmin {$subadminName} marked Dropoff request as Completed",
            ]);
        }

        // 2. Now check if both pickup and dropoff are completed for all assigned entries
        $bothCompleted = $requestBooking->assign->every(function ($assigned) {
            return $assigned->status == 1 &&
                !is_null($assigned->driver_id) &&
                !is_null($assigned->dropoff_driver_id);
        });

        if ($bothCompleted) {
            $requestBooking->status = 1; // mark main booking as completed
            $requestBooking->save();

            //Assign Loyalty Points when request booking is completed
             if (!$this->hasLoyaltyPointsAssigned($requestBooking->user_id, $requestBooking->id)) {
            $this->assignLoyaltyPoints($requestBooking->user_id, $requestBooking->car_id, $requestBooking->id);
            }
        }
    }
     elseif ($selfPickup === 'No' && $selfDropoff === 'Yes') {
        $allPickupDone = true;

        foreach ($requestBooking->assign as $assigned) {
            if (!is_null($assigned->driver_id) && $assigned->status != 1) {
                $assigned->status = 1;
                $assigned->save();

                Driver::where('id', $assigned->driver_id)->update(['is_available' => 1]);
            }

            if (!is_null($assigned->driver_id) && $assigned->status != 1) {
                $allPickupDone = false;
            }
        }

        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;

            SubAdminLog::create([
                'subadmin_id' => $subadmin->id,
                'section' => 'Dropoff Requests',
                'action' => 'Update Dropoff Status',
                'message' => "SubAdmin {$subadminName} marked Dropoff request as Completed",
            ]);
        }

        if ($allPickupDone) {
            $requestBooking->status = 1;
            $requestBooking->save();

            // 🎯 Assign Loyalty Points when request booking is completed
           if (!$this->hasLoyaltyPointsAssigned($requestBooking->user_id, $requestBooking->id)) { 
            $this->assignLoyaltyPoints($requestBooking->user_id, $requestBooking->car_id, $requestBooking->id);
           }
        }
    }

    return redirect()->route('requestbooking.index')->with('message', 'Dropoff Request Marked as Completed Successfully');
}

protected function hasLoyaltyPointsAssigned($userId, $bookingId)
{
    return DB::table('user_loyalty_earnings')
        ->where('user_id', $userId)
        ->where('request_booking_id', $bookingId)
        ->exists();
}

private function assignLoyaltyPoints($userId, $carId, $bookingId)
{
    $carDetails = CarDetails::where('car_id', $carId)->first();

    if ($carDetails) {
        $loyaltyPoints = LoyaltyPoints::where('car_id', $carDetails->id)->first();

        if ($loyaltyPoints) {
            $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->orderBy('id', 'desc')->first();
            $totalPoints = $userLoyalty ? $userLoyalty->total_points + $loyaltyPoints->on_car : $loyaltyPoints->on_car;

            UserLoyaltyEarning::create([
                'user_id'       => $userId,
                'request_booking_id'    => $bookingId,
                'total_points'  => $totalPoints,
                'earned_points' => $loyaltyPoints->on_car,
                'car_name'      => $carDetails->car_name,
                'discount'      => $loyaltyPoints->discount,
            ]);
            // 🔔 Send FCM Notification via Job
            $user = User::find($userId);
            if ($user && $user->fcm_token) {
                $title = 'Loyalty Points Earned';
                $description = "You've earned {$loyaltyPoints->on_car} loyalty points for booking {$carDetails->car_name}!";
                $data = [
                    'type' => 'loyalty_points',
                    'points' => $loyaltyPoints->on_car,
                    'car' => $carDetails->car_name,
                ];

                dispatch(new NotificationJob($user->fcm_token, $title, $description, $data));
            }
        }
    }
}

    

    public function destroy(Request $request, $id){
     $requestbooking = RequestBooking::find($id);
        $requestbookingName = $requestbooking->full_name;
    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;
        SubAdminLog::create([
            'subadmin_id' => Auth::guard('subadmin')->id(),
            'section' => ' Dropoff Requests',
            'action' => 'Delete',
            'message' => "SubAdmin {$subadminName} Deleted Dropoff Request of Customer {$requestbookingName}",
        ]);
    }
    $requestbooking->delete();
    return redirect()->route('requestbooking.index')->with(['message' => 'Dropoff Request Deleted Successfully']);
    }
}











 // Check if the driver is already assigned at the same time
    // $isDriverAssigned = RequestBooking::where('driver_id', $request->driver_id)
    // ->where('status', '!=', 1) // Exclude completed bookings
    // ->where(function ($query) use ($requestBooking) {
    //     $query->where('pickup_date', $requestBooking->pickup_date) // Same pickup date
    //           ->where(function ($q) use ($requestBooking) {
    //               $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                 ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                 ->orWhere(function ($subQuery) use ($requestBooking) {
    //                     $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
    //                              ->where('dropoff_time', '>', $requestBooking->dropoff_time);
    //                 });
    //           });
    // })
    // ->exists();












 // $isDriverAssigned = RequestBooking::where('driver_id', $request->driver_id)
    // ->where('status', '!=', 1) // Exclude completed bookings
    // ->where('id', '!=', $requestBooking->id) // Prevent checking the same booking
    // ->where(function ($query) use ($requestBooking) {
    //     // Case 1: Both Pickup and Dropoff Dates Exist
    //     if (!empty($requestBooking->pickup_date) && !empty($requestBooking->dropoff_date)) {
    //         $query->where(function ($q) use ($requestBooking) {
    //             $q->whereBetween('pickup_date', [$requestBooking->pickup_date, $requestBooking->dropoff_date])
    //               ->orWhereBetween('dropoff_date', [$requestBooking->pickup_date, $requestBooking->dropoff_date])
    //               ->orWhere(function ($subQuery) use ($requestBooking) {
    //                   $subQuery->where('pickup_date', '<', $requestBooking->pickup_date)
    //                            ->where('dropoff_date', '>', $requestBooking->dropoff_date);
    //               });
    //         })->where(function ($q) use ($requestBooking) {
    //             if (!empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_time)) {
    //                 $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                   ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                   ->orWhere(function ($subQuery) use ($requestBooking) {
    //                       $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
    //                                ->where('dropoff_time', '>', $requestBooking->dropoff_time);
    //                   });
    //             }
    //         });
    //     }
    //     // Case 2: Only Pickup Date Exists
    //     elseif (!empty($requestBooking->pickup_date)) {
    //         $query->where('pickup_date', $requestBooking->pickup_date)
    //               ->whereNotNull('pickup_date')
    //               ->where(function ($q) use ($requestBooking) {
    //                   if (!empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_time)) {
    //                       $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                         ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                         ->orWhere(function ($subQuery) use ($requestBooking) {
    //                             $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
    //                                      ->where('dropoff_time', '>', $requestBooking->dropoff_time);
    //                         });
    //                   }
    //               });
    //     }
    //     // Case 3: Only Dropoff Date Exists
    //     elseif (!empty($requestBooking->dropoff_date)) {
    //         $query->where('dropoff_date', $requestBooking->dropoff_date)
    //               ->whereNotNull('dropoff_date')
    //               ->where(function ($q) use ($requestBooking) {
    //                   if (!empty($requestBooking->pickup_time) && !empty($requestBooking->dropoff_time)) {
    //                       $q->whereBetween('pickup_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                         ->orWhereBetween('dropoff_time', [$requestBooking->pickup_time, $requestBooking->dropoff_time])
    //                         ->orWhere(function ($subQuery) use ($requestBooking) {
    //                             $subQuery->where('pickup_time', '<', $requestBooking->pickup_time)
    //                                      ->where('dropoff_time', '>', $requestBooking->dropoff_time);
    //                         });
    //                   }
    //               });
    //     }
        
    // })
    // ->exists();