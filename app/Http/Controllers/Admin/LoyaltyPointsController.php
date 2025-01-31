<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyPointRequest;

class LoyaltyPointsController extends Controller
{
    public function index()
    {
        $loyaltypoints = LoyaltyPoints::latest()->get();

        return view('admin.LoyaltyPoints.index',compact('loyaltypoints'));
    }

    public function create()
    {
        return view('admin.LoyaltyPoints.create');
    }

    public function store(LoyaltyPointRequest $request)
    {
        // return $request;

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     // 'email' => 'required|email|unique:drivers,email',
        //     'phone' => 'required|string|max:15',
        // ]);

        // $generatedPassword = random_int(10000000, 99999999);


        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // } else {
        //     $image = 'public/admin/assets/images/avator.png';
        // }

        $status = 1;

        // Create the user
        $loyaltyPoint = LoyaltyPoints::create([
            'on_referal' => $request->on_referal,
            // 'email' => $request->email,
            'on_car' => $request->on_car,
            'discount' => $request->discount,
            // 'password' => Hash::make($generatedPassword),
            // 'image' => $image,
            // 'status' => $status

        ]);

        // Mail::to($loyaltyPoints->email)->send(new loyaltyPointsCredentials($loyaltyPoints->name, $loyaltyPoints->email, $generatedPassword));

        return redirect()->route('loyaltypoints.index')->with(['message' => 'Loyalty Points Created Successfully']);
    }

    public function edit($id)
    {
        $loyaltyPoint = LoyaltyPoints::find($id);
        // return $user;
        return view('admin.LoyaltyPoints.edit', compact('loyaltyPoint'));
    }

    public function update(LoyaltyPointRequest $request, $id)
    {

        // Validate the incoming request
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     // 'email' => 'required|email|unique:drivers,email,' . $id,
        //     'phone' => 'required|string|max:15',
        // ]);

        $loyaltypoint = LoyaltyPoints::findOrFail($id);
        // Handle image upload
        // if ($request->hasFile('image')) {
        //     $destination = 'public/admin/assets/img/users/' . $driver->image;
        //     if (File::exists($destination)) {
        //         File::delete($destination);
        //     }

        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move('public/admin/assets/images/users', $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // } else {
        //     $image = $driver->image;
        // }

        // Update user details
        $loyaltypoint->update([
            'on_referal' => $request->on_referal,
            // 'email' => $request->email,
            'on_car' => $request->on_car,
            'discount' => $request->discount,
        ]);

        // Redirect back with a success message
        return redirect()->route('loyaltypoints.index')->with(['message' => 'Loyalty Points Updated Successfully']);
    }



    public function destroy($id)
    {
        LoyaltyPoints::destroy($id);
        return redirect()->route('loyaltypoints.index')->with(['message' => 'LoyaltyPoints Deleted Successfully']);
    }
}
