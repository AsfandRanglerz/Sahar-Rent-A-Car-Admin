<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\UserActivated;
use Illuminate\Http\Request;
use App\Mail\UserCredentials;
use App\Mail\UserDeActivated;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('documents')->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(UserRequest $request)
    {
        // return $request;

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     // 'email' => 'required|email|unique:users,email',
        //     'phone' => 'required|string|max:15',
        // ]);

        $validatedData = $request->validated();

        // $generatedPassword = random_int(10000000, 99999999);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } 
        else {
            // $image = 'public/admin/assets/images/avator.png';
            $image = null;
        }

        $status = 1;
        // $emirateId = $request->hasFile('emirate_id') ? $request->file('emirate_id')->store('documents', 'public') : null;
        // $passport = $request->hasFile('passport') ? $request->file('passport')->store('documents', 'public') : null;
        // $drivingLicense = $request->hasFile('driving_license') ? $request->file('driving_license')->store('documents', 'public') : null;
    
        // $document = User::firstOrCreate();
        // if ($request->hasFile('emirate_id')) {
        //     $document->emirate_id = $request->file('emirate_id')->store("documents/", 'public');
        //     // $document->emirate_id = $path;
        // }
        // if ($request->hasFile('passport')) {
        //     $document->passport = $request->file('passport')->store("documents/", 'public');
        //     // $document->passport = $path;
        // }
        // if ($request->hasFile('driving_license')) {
        //     $document->driving_license = $request->file('driving_license')->store("documents/", 'public');
        //     // $document->driving_license = $path;
        // }
        // $document->save();
        

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'address' => $request->address,
            // 'document' => $request->documents,
        //     'emirate_id' => $emirateId,
        // 'passport' => $passport,
        // 'driving_license' => $drivingLicense,
            'password' => Hash::make($request->password),
            'image' => $image,
            'status' => $status

        ]);

        // Mail::to($user->email)->send(new UserCredentials($user->name, $user->email, $generatedPassword));

        return redirect()->route('user.index')->with(['message' => 'Customer Created Successfully']);
    }


    public function edit($id)
    {
        $user = User::find($id);
        // return $user;
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

        // Validate the incoming request
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     // 'email' => 'required|email|unique:users,email,' . $id,
        //     'phone' => 'required|string|max:15',
        // ]);
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

        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'document' => $request->document,
            // 'address' => $request->address,
            'image' => $image,
        ]);

        // Redirect back with a success message
        return redirect()->route('user.index')->with(['message' => 'Customer Updated Successfully']);
    }



    public function destroy($id)
    {
        User::destroy($id);
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
