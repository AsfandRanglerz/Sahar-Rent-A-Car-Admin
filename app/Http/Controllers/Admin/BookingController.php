<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::latest()->get();
        return view('admin.booking.index',compact('bookings'));
    }

    public function create(){
        return view('admin.booking.create');
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
        $CarDetail = Booking::create([
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
            // 'car_play' => $request->features ? implode("\n", $request->features) : null, // Convert array to string
            // 'car_feature' => $request->car_feature,
            // 'delivery' => $request->delivery,
            // 'pickup' => $request->pickup,
            // 'travel_distance' => $request->travel_distance,
            // 'image' => $image,
            // 'status' => $status

        ]);

        // Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $generatedPassword));

        return redirect()->route('booking.index')->with(['message' => 'Booking Created Successfully']);
    } 

    public function destroy($id){
    Booking::destroy($id);
    return redirect()->route('booking.index')->with(['message' => 'Booking Deleted Successfully']);
    }
}
