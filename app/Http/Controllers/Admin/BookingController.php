<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Booking;
use App\Models\SubAdminLog;
use Illuminate\Http\Request;
use App\Models\RequestBooking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        // $bookings = Booking::latest()->get();
        // $bookings = Booking::orderBy('status','ASC')->get();
        
        $apiBookings = Booking::orderBy('status','ASC')->get();
        $requestBookings = RequestBooking::with(['driver','booking', 'assign.pickupdriver', 'assign.dropoffdriver']) 
        ->whereIn('status', [1])
        ->orderBy('status','ASC')
        ->get();
        $bookings = $apiBookings->merge($requestBookings);
        $bookings = $bookings->sortBy('status');
        return view('admin.booking.index',compact('bookings'));
    }

    public function activeBookingsCounter()
    {
        $activeBookings = Booking::where('status', 0)->count();
        // $activeRequestBookings = RequestBooking::where('status', 0)->count();

        $totalActive = $activeBookings + $activeRequestBookings;
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
    if (!$booking) {
        $booking = RequestBooking::find($id);
    }

    // If still not found, return an error
    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found.']);
    }

    // Update status to completed
    $booking->status = $request->status;
    $booking->save();

    if ($request->status == 1) {
        DB::table('assigned_requests')
            ->where('request_booking_id', $booking->id)
            ->update(['status' => 1]);  // Mark as completed
    }
    
    if (Auth::guard('subadmin')->check()) {
        $subadmin = Auth::guard('subadmin')->user();
        $subadminName = $subadmin->name;

        SubAdminLog::create([
            'subadmin_id' => $subadmin->id,
            'section' => 'Bookings',
            'action' => 'Update Status',
            'message' => "SubAdmin: {$subadminName} updated  status.",
        ]);
    }
    return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
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
            'message' => "SubAdmin: {$subadminName} deleted a booking",
        ]);
    }
    $booking->delete();
    return redirect()->route('booking.index')->with(['message' => 'Booking Deleted Successfully']);
    }
}
