<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubAdminLog;
use Illuminate\Http\Request;
use App\Models\LoyaltyPoints;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            'added_by_subadmin' => Auth::guard('subadmin')->id(),
        ]);
        if (Auth::guard('subadmin')->check()) {
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'LoyaltyPoints',
                'action' => 'Add',
                'message' => 'Added LoyaltyPoints ',
            ]);
        }
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
        // if (Auth::guard('subadmin')->check()) {
        //     SubAdminLog::create([
        //         'subadmin_id' => Auth::guard('subadmin')->id(),
        //         'section' => 'LoyaltyPoints',
        //         'action' => 'Edit',
        //         'message' => 'Updated  LoyaltyPoints',
        //     ]);
        // }
        // $editedBy = Auth::guard('subadmin')->user(); // Get the subadmin object
        // $addedBy = $loyaltypoint->added_by_subadmin;
        
        // if ($editedBy->id !== $addedBy) {
        //     $message = "Loyalty Points updated by SubAdmin: " . $editedBy->name ;
        // } else {
        //     $message = "Loyalty Points Original updated by SubAdmin: " . $editedBy->name ;
        // }
        
        // // Log the edit
        // SubAdminLog::create([
        //     'subadmin_id' => $editedBy->id,
        //     'section' => 'LoyaltyPoints',
        //     'action' => 'Edit',
        //     'message' => $message,
        // ]);
        $editedBy = Auth::guard('subadmin')->user();

// Only log if a subadmin is editing
if ($editedBy) {
    $message = "Loyalty Point updated by SubAdmin: " . $editedBy->name . " - Updated Loyalty Point: " . $request->name;

    SubAdminLog::create([
        'subadmin_id' => $editedBy->id,
        'section' => 'Loyalty Points',
        'action' => 'Edit',
        'message' => $message,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
        // Redirect back with a success message
        return redirect()->route('loyaltypoints.index')->with(['message' => 'Loyalty Points Updated Successfully']);
    }



    public function destroy(Request $request, $id)
    {
        LoyaltyPoints::destroy($id);
        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'LoyaltyPoints',
                'action' => 'Delete',
                'message' => "SubAdmin: {$subadminName} Deleted  LoyaltyPoints",
            ]);
        }

        return redirect()->route('loyaltypoints.index')->with(['message' => 'LoyaltyPoints Deleted Successfully']);
    }
}
