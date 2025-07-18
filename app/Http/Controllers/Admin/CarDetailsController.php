<?php

namespace App\Http\Controllers\Admin;

use App\Models\CarDetails;
use App\Models\SubAdminLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CarRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CarDetailsController extends Controller
{
    public function index()
    {
        $CarDetails = CarDetails::latest()->get();

        return view('admin.CarFleet.CarDetail.index',compact('CarDetails'));
    }

    public function create()
    {
        return view('admin.CarFleet.CarDetail.create');
    }

    public function store(CarRequest $request)
    {
        // return $request;
        $validatedData = $request->validated();

       

        // $generatedPassword = random_int(10000000, 99999999);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = null;
        }

        do {
            $carId = mt_rand(1000, 9999); //  Can be replaced this with a custom logic for 4-digit IDs
        } while (CarDetails::where('car_id', $carId)->exists());

        $status = 1;

        // Create the user
        $CarDetail = CarDetails::create([
            'car_id' => $carId,
            'car_name' => $request->car_name,
            'availability' => $request->availability,
            'price_per_day' => $request->price_per_day,
            'durations' => $request->durations,
            'call_number' => $request->call_number,
            // 'whatsapp_number' => $request->whatsapp_number,
            'passengers' => $request->passengers,
            'luggage' => $request->luggage,
            'doors' => $request->doors,
            'car_type' => $request->car_type,
            'feature' => $request->feature,
            'feature' => $request->features ? implode("\n", $request->features) : null, // Convert array to string
            'price_per_week' => $request->price_per_week,
            'price_per_two_week' => $request->price_per_two_week,
            'price_per_three_week' => $request->price_per_three_week,
            'price_per_month' => $request->price_per_month,
            // 'delivery' => $request->delivery,
            // 'pickup' => $request->pickup,
            // 'travel_distance' => $request->travel_distance,
            'image' => $image,
            'status' => $request->status,
        'added_by_subadmin' => Auth::guard('subadmin')->id(),
        ]);
        if (Auth::guard('subadmin')->check()) {
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'Car Inventory',
                'action' => 'Add',
                'message' => 'Added Cars Inventory ' . $CarDetail->car_name,
            ]);
        }
        // Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $generatedPassword));

        return redirect()->route('car.index')->with(['message' => 'Cars Inventory Created Successfully']);
    }

    public function edit($id)
    {
        $CarDetail = CarDetails::find($id);
        // return $user;
        return view('admin.CarFleet.CarDetail.edit', compact('CarDetail'));
    }

    public function update(CarRequest $request, $id)
    {
        $validatedData = $request->validated();

        // Validate the incoming request    
        // $request->validate([
        //     'car_name' => 'required|string|max:255',
        //     'sanitized' => 'required|numeric',
        //     'car_feature' => 'required|numeric',
        //     'passengers' => 'required|numeric|max:10',
        //     'luggage' => 'required|numeric|max:255',
        //     'doors' => 'required|numeric|max:10',
        //     'car_type' => 'required|string|max:255',
        //     // 'email' => 'required|email|unique:drivers,email',
        //     'call_number' => 'required|numeric|min:11',
        //     'whatsapp_number' => 'required|numeric|min:11',
        //     'pricing' => ['required','regex:/^\d+(\.\d{1,2})?$/'],
        // ]);

        $CarDetail = CarDetails::findOrFail($id);
       // Handle image upload

        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $CarDetail->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $CarDetail->image;
        }

        // Update user details
        $CarDetail->update([
            'car_name' => $request->car_name,
            'availability' => $request->availability,
            'price_per_day' => $request->price_per_day,
            'durations' => $request->durations,
            'call_number' => $request->call_number,
            // 'whatsapp_number' => $request->whatsapp_number,
            'passengers' => $request->passengers,
            'luggage' => $request->luggage,
            'doors' => $request->doors,
            'car_type' => $request->car_type,
            'feature' => $request->feature,
            'feature' => $request->features ? implode("\n", $request->features) : null, // Convert array to string
            'price_per_week' => $request->price_per_week,
            'price_per_two_week' => $request->price_per_two_week,
            'price_per_three_week' => $request->price_per_three_week,
            'price_per_month' => $request->price_per_month,
            // 'delivery' => $request->delivery,
            // 'pickup' => $request->pickup,
            // 'travel_distance' => $request->travel_distance,
            'image' => $image,
            'status' => $request->status
        ]);
        // if (Auth::guard('subadmin')->check()) {
        //     SubAdminLog::create([
        //         'subadmin_id' => Auth::guard('subadmin')->id(),
        //         'section' => 'Car Inventory',
        //         'action' => 'edit',
        //         'message' => 'Updated Car Inventory: ' . $request->car_name,
        //     ]);
        // }
        // $editedBy = Auth::guard('subadmin')->user(); // Get the subadmin object
        // $addedBy = $CarDetail->added_by_subadmin;
        
        // if ($editedBy->id !== $addedBy) {
        //     $message = "Car Inventory updated by SubAdmin: " . $editedBy->name . " - Updated Car Inventory: " . $request->name;
        // } else {
        //     $message = "Car Inventory updated by SubAdmin: " . $editedBy->name . " - Updated Car Inventory: " . $request->name;
        // }
        
        // // Log the edit
        // SubAdminLog::create([
        //     'subadmin_id' => $editedBy->id,
        //     'section' => 'Cars Inventory',
        //     'action' => 'Edit',
        //     'message' => $message,
        // ]);
        $editedBy = Auth::guard('subadmin')->user();

// Only log if a subadmin is editing
if ($editedBy) {
    $message = "Cars Inventory updated by SubAdmin " . $editedBy->name . " - Updated Cars Inventory " . $request->name;

    SubAdminLog::create([
        'subadmin_id' => $editedBy->id,
        'section' => 'Cars Inventory',
        'action' => 'Edit',
        'message' => $message,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
        // Redirect back with a success message
        return redirect()->route('car.index')->with(['message' => 'Cars Inventory Updated Successfully']);
    }



    public function destroy(Request $request, $id)
    {
        $carDetail = CarDetails::find($id);
        $carDetailName = $carDetail->car_id;
        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'Car Inventory',
                'action' => 'delete',
                'message' => "SubAdmin {$subadminName} deleted Cars Inventory {$carDetailName}",
            ]);
        }
        $carDetail->delete();
        return redirect()->route('car.index')->with(['message' => 'Cars Inventory Deleted Successfully']);
    }
}
