<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\UserActivated;
use App\Models\SubAdminLog;
use Illuminate\Http\Request;
use App\Mail\UserCredentials;
use App\Mail\UserDeActivated;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('documents')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        
        return view('admin.user.create');
    }

    public function store(UserRequest $request)
    {
        // return $request;

        
        $validatedData = $request->validated();

        // $generatedPassword = random_int(10000000, 99999999);
        $emirate_id = null;
        $passport = null;
        $driving_license = null;
        $plainPassword = $request->password;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } 
        else {
            $image = 'public/admin/assets/images/avator.png';
            // $image = null;
        }

        $status = 1;
        // $emirateId = $request->hasFile('emirate_id') ? $request->file('emirate_id')->store('documents', 'public') : null;
        // $passport = $request->hasFile('passport') ? $request->file('passport')->store('documents', 'public') : null;
        // $drivingLicense = $request->hasFile('driving_license') ? $request->file('driving_license')->store('documents', 'public') : null;
    
        // $document = User::firstOrCreate();
        // if ($request->hasFile('emirate_id')) {
        //     $emirate_id = $request->file('emirate_id')->store("documents/", 'public');
        //     // $emirate_id = $path;
        // }
        // if ($request->hasFile('passport')) {
        //     $passport = $request->file('passport')->store("documents/", 'public');
        //     // $passport = $path;
        // }
        // if ($request->hasFile('driving_license')) {
        //     $driving_license = $request->file('driving_license')->store("documents/", 'public');
        //     // $document->driving_license = $path;
        // }
        // $document->save();
        if ($request->hasFile('emirate_id')) {
            $file = $request->file('emirate_id');
            $filename = time() . '_emirate_id_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $emirate_id = 'public/admin/assets/images/users/' . $filename;
        }
        
        if ($request->hasFile('passport')) {
            $file = $request->file('passport');
            $filename = time() . '_passport_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $passport = 'public/admin/assets/images/users/' . $filename;
        }
        
        if ($request->hasFile('driving_license')) {
            $file = $request->file('driving_license');
            $filename = time() . '_driving_license_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $driving_license = 'public/admin/assets/images/users/' . $filename;
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'address' => $request->address,
            // 'document' => $request->documents,
            'emirate_id' => $emirate_id,
            'passport' => $passport,
            'driving_license' => $driving_license,
            'password' => Hash::make($plainPassword),
            'image' => $image,
            'status' => $status,
            'added_by_subadmin' => Auth::guard('subadmin')->id(), // Store subadmin ID
        ]);

        if (Auth::guard('subadmin')->check()) {
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'Customers',
                'action' => 'Add',
                'message' => 'Added Customer ' . $user->name,
            ]);
        }

        // if (app()->environment('production')) {
        Mail::to($user->email)->send(new UserCredentials($user->name, $user->email, $user->phone, $plainPassword));
        // }
        return redirect()->route('user.index')->with(['message' => 'Customer Created Successfully']);
    }


    public function edit($id)
    {
        $user = User::find($id);
       
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        

        // Validate the incoming request
        $request->validate([
            // 'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $id,
            // 'phone' => 'required|string|max:15',
            'emirate_id' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
            'passport' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
            'driving_license' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
        ],[
            
            'emirate_id.required' => 'Emirate ID is required',
            'emirate_id.file' => 'Emirate ID must be a file',
            'emirate_id.mimes' => 'Emirate ID must be a file of type: jpeg, png, jpg, svg',
            'emirate_id.max' => 'Emirate ID must not exceed size 2MB',
            'passport.required' => 'Passport is required',
            'passport.file' => 'Passport must be a file',
            'passport.mimes' => 'Passport must be a file of type: jpeg, png, jpg, svg',
            'passport.max' => 'Passport must not exceed size 2MB',
            'driving_license.required' => 'Driving License is required',
            'driving_license.file' => 'Driving License must be a file',
            'driving_license.mimes' => 'Driving License must be a file of type: jpeg, png, jpg, svg',
            'driving_license.max' => 'Driving License must not exceed size 2MB',
        ]);
        // $validatedData = $request->validated();

        $user = User::findOrFail($id);
        // Handle image upload
        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $user->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $user->image;
        }

        $emirate_id = $user->emirate_id;
        $passport = $user->passport;
        $driving_license = $user->driving_license;

        // if ($request->hasFile('emirate_id')) {
        //     $emirate_id = $request->file('emirate_id')->store("documents/", 'public');
        //     // $emirate_id = $path;
        // }
        // if ($request->hasFile('passport')) {
        //     $passport = $request->file('passport')->store("documents/", 'public');
        //     // $passport = $path;
        // }
        // if ($request->hasFile('driving_license')) {
        //     $driving_license = $request->file('driving_license')->store("documents/", 'public');
        //     // $document->driving_license = $path;
        // }
        if ($request->hasFile('emirate_id')) {
            $file = $request->file('emirate_id');
            $filename = time() . '_emirate_id_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $emirate_id = 'public/admin/assets/images/users/' . $filename;
        }
        
        if ($request->hasFile('passport')) {
            $file = $request->file('passport');
            $filename = time() . '_passport_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $passport = 'public/admin/assets/images/users/' . $filename;
        }
        
        if ($request->hasFile('driving_license')) {
            $file = $request->file('driving_license');
            $filename = time() . '_driving_license_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $driving_license = 'public/admin/assets/images/users/' . $filename;
        }
        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'document' => $request->document,
            'emirate_id' => $emirate_id,
            'passport' => $passport,
            'driving_license' => $driving_license,
            // 'address' => $request->address,
            'image' => $image,
        ]);
        // if (Auth::guard('subadmin')->check()) {
        //     SubAdminLog::create([
        //         'subadmin_id' => Auth::guard('subadmin')->id(),
        //         'section' => 'Customers',
        //         'action' => 'Edit',
        //         'message' => 'Customer updated: ' . $request->name,
        //     ]);
        // }
        // $editedBy = Auth::guard('subadmin')->user(); // Get the subadmin object
        
        // $addedBy = $user->added_by_subadmin;
        
        
        // if ($editedBy->id !== $addedBy) {
        //     $message = "Customer updated by SubAdmin: " . $editedBy->name . " - Updated Customer: " . $request->name;
        // } else {
        //     $message = "Customer updated by SubAdmin: " . $editedBy->name . " - Updated Customer: " . $request->name;
        // }
        
        // // Log the edit
        // SubAdminLog::create([
        //     'subadmin_id' => $editedBy->id,
        //     'section' => 'Customers',
        //     'action' => 'Edit',
        //     'message' => $message,
        // ]);
        // Check if a subadmin is logged in
$editedBy = Auth::guard('subadmin')->user();

// Only log if a subadmin is editing
if ($editedBy) {
    $message = "SubAdmin " . $editedBy->name . " Updated Customer " . $request->name;

    SubAdminLog::create([
        'subadmin_id' => $editedBy->id,
        'section' => 'Customers',
        'action' => 'Edit',
        'message' => $message,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}


        // Redirect back with a success message
        return redirect()->route('user.index')->with(['message' => 'Customer Updated Successfully']);
    }



    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $customerName = $user->name;
        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'Customers',
                'action' => 'Delete',
                'message' => "SubAdmin {$subadminName} Deleted Customer {$customerName}",
            ]);
        }
        $user->delete();
        return redirect()->route('user.index')->with(['message' => 'Customer Deleted Successfully']);
    }



    public function active(Request $request, $id)
    {
        // return $request;
        // Find the user by ID
        $data = User::find($id);

        // Update the user's `is_active` status
        $data->update([
            'status' => $request->status,
        ]);

        // Prepare message data
        $message['name'] = $data->name;


        try {
            // Send an email based on `sendCredentials`

            Mail::to($data->email)->send(new UserActivated($message));


            return redirect()->route('user.index')->with([
                'status' => true,
                'message' => 'Customer Activated Successfully',
            ]);
        } catch (\Throwable $th) {
            // Handle email sending errors
            return back()->with([
                'status' => false,
                'message' => 'Failed to send email: ' . $th->getMessage(),
            ]);
        }
    }



    public function deactive(Request $request, $id)
    {
        // return $request;
        $reason = $request->reason;
        $data = User::find($id);
        // return $data;

        $data->update([
            'status' => $request->status,
        ]);

        $message['reason'] = $reason;
        $message['name'] = $data->name;

        try {
            Mail::to($data->email)->send(new UserDeActivated($message));
            return redirect()->route('user.index')->with(['message' => 'Customer Deactivated Successfully']);
        } catch (\throwable $th) {
            dd($th->getMessage());
            return back()->with(['status' => false, 'message' => $th->getMessage()]);
        }
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }
}
