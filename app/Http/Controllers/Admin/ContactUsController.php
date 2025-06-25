<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use App\Models\SubAdminLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContactUsController extends Controller
{
    public function index()
    {
        $contactuss = ContactUs::all();
        return view('admin.ContactUs.index', compact('contactuss'));
    }

    // public function create()
    // {
        
    //     return view('admin.user.create');
    // }

    // public function store(UserRequest $request)
    // {
    //     // return $request;

        
    //     $validatedData = $request->validated();

    //     // $generatedPassword = random_int(10000000, 99999999);
    //     $emirate_id = null;
    //     $passport = null;
    //     $driving_license = null;
    //     $plainPassword = $request->password;

    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move(public_path('admin/assets/images/users/'), $filename);
    //         $image = 'public/admin/assets/images/users/' . $filename;
    //     } 
    //     else {
    //         // $image = 'public/admin/assets/images/avator.png';
    //         $image = null;
    //     }

    //     $status = 1;
    //     // $emirateId = $request->hasFile('emirate_id') ? $request->file('emirate_id')->store('documents', 'public') : null;
    //     // $passport = $request->hasFile('passport') ? $request->file('passport')->store('documents', 'public') : null;
    //     // $drivingLicense = $request->hasFile('driving_license') ? $request->file('driving_license')->store('documents', 'public') : null;
    
    //     // $document = User::firstOrCreate();
    //     if ($request->hasFile('emirate_id')) {
    //         $emirate_id = $request->file('emirate_id')->store("documents/", 'public');
    //         // $emirate_id = $path;
    //     }
    //     if ($request->hasFile('passport')) {
    //         $passport = $request->file('passport')->store("documents/", 'public');
    //         // $passport = $path;
    //     }
    //     if ($request->hasFile('driving_license')) {
    //         $driving_license = $request->file('driving_license')->store("documents/", 'public');
    //         // $document->driving_license = $path;
    //     }
    //     // $document->save();
        

    //     // Create the user
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         // 'address' => $request->address,
    //         // 'document' => $request->documents,
    //         'emirate_id' => $emirate_id,
    //         'passport' => $passport,
    //         'driving_license' => $driving_license,
    //         'password' => Hash::make($plainPassword),
    //         'image' => $image,
    //         'status' => $status,
    //         'added_by_subadmin' => Auth::guard('subadmin')->id(), // Store subadmin ID
    //     ]);

    //     if (Auth::guard('subadmin')->check()) {
    //         SubAdminLog::create([
    //             'subadmin_id' => Auth::guard('subadmin')->id(),
    //             'section' => 'Customers',
    //             'action' => 'Add',
    //             'message' => 'Added customer: ' . $user->name,
    //         ]);
    //     }

    //     Mail::to($user->email)->send(new UserCredentials($user->name, $user->email, $user->phone, $plainPassword));

    //     return redirect()->route('user.index')->with(['message' => 'Customer Created Successfully']);
    // }


    public function edit($id)
    {
        $contactus = ContactUs::find($id);
       
        return view('admin.ContactUs.edit', compact('contactus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);
        // $validatedData = $request->validated();

        $contactus = ContactUs::findOrFail($id);
        
        // Update user details
        $contactus->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        // Check if a subadmin is logged in
$editedBy = Auth::guard('subadmin')->user();

// Only log if a subadmin is editing
if ($editedBy) {
    $message = "ContactUs updated by SubAdmin " . $editedBy->name;

    SubAdminLog::create([
        'subadmin_id' => $editedBy->id,
        'section' => 'ContactUs',
        'action' => 'Edit',
        'message' => $message,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}


        // Redirect back with a success message
        return redirect()->route('ContactUs.index')->with(['message' => 'ContactUs Updated Successfully']);
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
                'message' => "SubAdmin {$subadminName} deleted customer {$customerName}",
            ]);
        }
        $user->delete();
        return redirect()->route('user.index')->with(['message' => 'Customer Deleted Successfully']);
    }
}
