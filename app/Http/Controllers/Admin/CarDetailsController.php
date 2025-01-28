<?php

namespace App\Http\Controllers\Admin;

use App\Models\CarDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CarRequest;
use App\Http\Controllers\Controller;
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

        // $generatedPassword = random_int(10000000, 99999999);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/avator.png';
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
            'pricing' => $request->pricing,
            'durations' => $request->durations,
            // 'call_number' => $request->call_number,
            // 'whatsapp_number' => $request->whatsapp_number,
            'passengers' => $request->passengers,
            'luggage' => $request->luggage,
            'doors' => $request->doors,
            'car_type' => $request->car_type,
            'car_play' => $request->car_play,
            'car_play' => $request->features ? implode("\n", $request->features) : null, // Convert array to string
            'sanitized' => $request->sanitized,
            'car_feature' => $request->car_feature,
            // 'delivery' => $request->delivery,
            // 'pickup' => $request->pickup,
            // 'travel_distance' => $request->travel_distance,
            'image' => $image,
            // 'status' => $status

        ]);

        // Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $generatedPassword));

        return redirect()->route('car.index')->with(['message' => 'Car Inventory Created Successfully']);
    }

    public function edit($id)
    {
        $CarDetail = CarDetails::find($id);
        // return $user;
        return view('admin.CarFleet.CarDetail.edit', compact('CarDetail'));
    }

    public function update( CarRequest $request, $id)
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
            'pricing' => $request->pricing,
            'durations' => $request->durations,
            // 'call_number' => $request->call_number,
            // 'whatsapp_number' => $request->whatsapp_number,
            'passengers' => $request->passengers,
            'luggage' => $request->luggage,
            'doors' => $request->doors,
            'car_type' => $request->car_type,
            'car_play' => $request->car_play,
            'car_play' => $request->features ? implode("\n", $request->features) : null, // Convert array to string
            'sanitized' => $request->sanitized,
            'car_feature' => $request->car_feature,
            // 'delivery' => $request->delivery,
            // 'pickup' => $request->pickup,
            // 'travel_distance' => $request->travel_distance,
            'image' => $image,
            'status' => $request->status
        ]);

        // Redirect back with a success message
        return redirect()->route('car.index')->with(['message' => 'Car Inventory Updated Successfully']);
    }



    public function destroy($id)
    {
        CarDetails::destroy($id);
        return redirect()->route('car.index')->with(['message' => 'Car Inventory Deleted Successfully']);
    }
}
