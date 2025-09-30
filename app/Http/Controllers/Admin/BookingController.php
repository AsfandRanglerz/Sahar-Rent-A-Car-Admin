<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\User;
use App\Models\Booking;
use App\Models\CarDetails;
use App\Models\SubAdminLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\NotificationJob;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use App\Models\UserLoyaltyEarning;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // $bookings = Booking::latest()->get();
        // $bookings = Booking::orderBy('status','ASC')->get();
        $payment = $request->get('payment_method');
        $query = Booking::whereIn('status', [1,0]);
        if ($request->filled('start_date')) {
            $query->whereDate('pickup_date', '=', $request->start_date);
        }
    
        // Apply End Date Filter
        if ($request->filled('end_date')) {
            $query->whereDate('dropoff_date', '=', $request->end_date);
        }
         if ($payment) {
            $query =$query->where('payment_method', $payment);
        }
        $apiBookings = $query->latest()->get();
        // $bookingIncome = $query->whereNotNull('price')->sum('price');
        $requestQuery = RequestBooking::with(['driver','booking', 'assign.pickupdriver', 'assign.dropoffdriver']) 
        ->whereIn('status', [1]);
        if ($request->filled('start_date')) {
            $requestQuery->whereDate('pickup_date', '=', $request->start_date);
        }
    
        // Apply End Date Filter
        if ($request->filled('end_date')) {
            $requestQuery->whereDate('dropoff_date', '=', $request->end_date);
        }
        if ($payment) {
        $requestQuery = $requestQuery->where('payment_method', $payment);
        }
        $requestBookings = $requestQuery->latest()->get();
        $bookings = $apiBookings->merge($requestBookings);
        $bookings = $bookings->filter(function ($booking) use ($request) {
            $pickupDate = $booking->pickup_date ?? null;
            $dropoffDate = $booking->dropoff_date ?? null;
        
            if ($request->filled('start_date')) {
                if (!$pickupDate || $pickupDate < $request->start_date) {
                    return false;
                }
            }
        
            if ($request->filled('end_date')) {
                if (!$dropoffDate || $dropoffDate > $request->end_date) {
                    return false;
                }
            }
        
            return true;
        });
         

        $bookings = $bookings->sortBy('status');
        // $requestQuery = RequestBooking::whereIn('status', [2,1]);
        // $requestIncome = $requestQuery->whereNotNull('price')->sum('price');
         if($payment){
            // Get booking IDs with selected payment method
            $bookingIds = \DB::table('bookings')->where('payment_method', $payment)->where('status', [0,1])->pluck('id')->toArray();
            $requestBookingIds = \DB::table('request_bookings')->where('payment_method', $payment)->where('status', 1)->pluck('id')->toArray();

            $totalIncome = \DB::table('booking_totals')
                ->where(function($query) use ($bookingIds, $requestBookingIds) {
                    $query->whereIn('booking_id', $bookingIds)
                          ->orWhereIn('request_booking_id', $requestBookingIds);
                })
                ->sum('total_price');
        }
        else {
            $totalIncome = \DB::table('booking_totals')->sum('total_price');
        }
        $contactAddress = DB::table('contact_us')->value('address');
        
        return view('admin.booking.index',compact('bookings', 'totalIncome', 'contactAddress'));
    }

    public function activeBookingsCounter()
    {
        $activeBookings = Booking::where('status', 0)->count();
        // $activeRequestBookings = RequestBooking::where('status', 0)->count();

        $totalActive = $activeBookings;
        return response()->json(['count' => $totalActive]);
    }
    
    public function create(){
        return view('admin.booking.create');
    }
    public function updateStatus(Request $request, $id)
{
    // First, check if the booking exists in the Booking table
    $booking = Booking::find($id);

    // If not found in Booking, check in RequestBooking
    // if (!$booking) {
    //     $booking = RequestBooking::find($id);
    // }

    // If still not found, return an error
    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found.']);
    }

    // Update status to completed
    $booking->status = $request->status;
    $booking->save();
    if ($request->status == 1) {
        // 🎯 Assign Loyalty Points when booking is marked completed
        $this->assignLoyaltyPoints($booking->user_id, $booking->car_id);
    }
    // if ($request->status == 1) {
    //     DB::table('assigned_requests')
    //         ->where('request_booking_id', $booking->id)
    //         ->update(['status' => 1]);  // Mark as completed
    // }
    
    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;

        SubAdminLog::create([
            'subadmin_id' => $subadmin->id,
            'section' => 'Bookings',
            'action' => 'Update Status',
            'message' => "SubAdmin {$subadminName} Updated  Status",
        ]);
    }
    return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
}

private function assignLoyaltyPoints($userId, $carId)
{
    $carDetails = CarDetails::where('car_id', $carId)->first();

    if ($carDetails) {
        $loyaltyPoints = LoyaltyPoints::where('car_id', $carDetails->id)->first();

        if ($loyaltyPoints) {
            $userLoyalty = UserLoyaltyEarning::where('user_id', $userId)->orderBy('id', 'desc')->first();
            $totalPoints = $userLoyalty ? $userLoyalty->total_points + $loyaltyPoints->on_car : $loyaltyPoints->on_car;

            UserLoyaltyEarning::create([
                'user_id'       => $userId,
                'total_points'  => $totalPoints,
                'earned_points' => $loyaltyPoints->on_car,
                'car_name'      => $carDetails->car_name,
                'discount'      => $loyaltyPoints->discount,
            ]);

            $user = User::find($userId);
            if ($user && $user->fcm_token) {
                $title = 'Loyalty Points Earned';
                $description = "You've earned {$loyaltyPoints->on_car} loyalty points for booking {$carDetails->car_name}!";
                $data = [
                    'type' => 'loyalty_points',
                    'points' => $loyaltyPoints->on_car,
                    'car' => $carDetails->car_name,
                    'user_id' => $userId,
                ];

                dispatch(new NotificationJob($user->fcm_token, $title, $description, $data));
            }

            Notification::create([
                'customer_id'  => $userId,
                'title'        => 'Loyalty Points Earned!',
                'description'  => 'You earned ' . $loyaltyPoints->on_car . ' points for booking ' . $carDetails->car_name . '.',
                // 'seenByUser'   => false,
            ]);

        }
    }
}

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

        do {
            $carId = mt_rand(1000, 9999); //  Can be replaced this with a custom logic for 4-digit IDs
        } while (Booking::where('car_id', $carId)->exists());

        $status = 1;

        // Create the user
        $booking = Booking::create([
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

        return redirect()->route('booking.index')->with(['message' => 'Booking Created Successfully']);
    } 

    public function destroy(Request $request, $id){
        // return $id;
     $booking = RequestBooking::find($id);
     if (!$booking) {
        $booking = Booking::find($id);
    }
    // if (!$booking) {
    //     return redirect()->route('booking.index')->with(['error' => "Booking with ID {$id} not found."]);
    // }
        $bookingName = $booking->name;
    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;
        SubAdminLog::create([
            'subadmin_id' => Auth::guard('subadmin')->id(),
            'section' => 'Bookings',
            'action' => 'Delete',
            'message' => "SubAdmin {$subadminName} Deleted a booking",
        ]);
    }
    $booking->delete();
    return redirect()->route('booking.index')->with(['message' => 'Booking Deleted Successfully']);
    }
}
