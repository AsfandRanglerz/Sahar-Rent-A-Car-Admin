<?php

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Mail\DriverActivated;
use App\Mail\DriverCredentials;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\DriverDeActivated;


class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::latest()->get();

        return view('admin.driver.index',compact('drivers'));
    }

    public function create()
    {
        return view('admin.driver.create');
    }

    public function store(Request $request)
    {
        // return $request;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'required|string|max:15',
        ]);

        $generatedPassword = random_int(10000000, 99999999);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/avator.png';
        }

        $status = 1;

        // Create the user
        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($generatedPassword),
            'image' => $image,
            'status' => $status

        ]);

        Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $generatedPassword));

        return redirect()->route('driver.index')->with(['message' => 'Driver Created Successfully']);
    }

    public function edit($id)
    {
        $driver = Driver::find($id);
        // return $user;
        return view('admin.driver.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email,' . $id,
            'phone' => 'required|string|max:15',
        ]);

        $driver = Driver::findOrFail($id);
        // Handle image upload
        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $driver->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $driver->image;
        }

        // Update user details
        $driver->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $image,
        ]);

        // Redirect back with a success message
        return redirect()->route('driver.index')->with(['message' => 'Driver Updated Successfully']);
    }



    public function destroy($id)
    {
        Driver::destroy($id);
        return redirect()->route('driver.index')->with(['message' => 'Driver Deleted Successfully']);
    }









    public function active(Request $request, $id)
    {
        // return $request;
        // Find the user by ID
        $data = Driver::find($id);

        // Update the user's `is_active` status
        $data->update([
            'status' => $request->status,
        ]);

        // Prepare message data
        $message['name'] = $data->name;


        try {
            // Send an email based on `sendCredentials`

            Mail::to($data->email)->send(new DriverActivated($message));


            return redirect()->route('driver.index')->with([
                'status' => true,
                'message' => 'Driver Activated Successfully',
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
        $data = Driver::find($id);
        // return $data;

        $data->update([
            'status' => $request->status,
        ]);

        $message['reason'] = $reason;
        $message['name'] = $data->name;

        try {
            Mail::to($data->email)->send(new DriverDeActivated($message));
            return redirect()->route('driver.index')->with(['message' => 'Driver Deactivated Successfully']);
        } catch (\throwable $th) {
            dd($th->getMessage());
            return back()->with(['status' => false, 'message' => $th->getMessage()]);
        }
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }
}
