<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Subadmin;
use Illuminate\Http\Request;
use App\Mail\SubAdminActivated;
use App\Mail\SubadminCredentials;
use App\Mail\SubAdminDeActivated;

use App\Models\SubAdminPermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SubadminController extends Controller
{
    public function index()
    {
        $subadmins = Subadmin::latest()->get();

        return view('admin.subadmin.index', compact('subadmins'));
    }

    public function create()
    {
        return view('admin.subadmin.create');
    }

    public function store(Request $request)
    {
        // return $request;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:subadmins,email',
            'phone' => 'required|string|max:15',
        ]);

        // $generatedPassword = random_int(10000000, 99999999);
        $plainPassword = $request->password;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = null;
        }

        $status = 1;

        // Create the user
        $user = Subadmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($plainPassword),
            'image' => $image,
            'status' => $status

        ]);

        Mail::to($user->email)->send(new SubadminCredentials($user->name, $user->email, $plainPassword));

        return redirect()->route('subadmin.index')->with(['message' => 'Subadmin Created Successfully']);
    }

    public function edit($id)
    {
        $subadmin = Subadmin::find($id);
        // return $user;
        return view('admin.subadmin.edit', compact('subadmin'));
    }

    public function update(Request $request, $id)
    {
        // Find the user by ID

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:subadmins,email,' . $id,
            'phone' => 'required|string|max:15',
        ]);

        $subadmin = Subadmin::findOrFail($id);
        // Handle image upload
        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $subadmin->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = null;
        }

        // Update user details
        $subadmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $image,
        ]);

        // Redirect back with a success message
        return redirect()->route('subadmin.index')->with(['message' => 'Subadmin Updated Successfully']);
    }



    public function destroy($id)
    {
        Subadmin::destroy($id);
        return redirect()->route('subadmin.index')->with(['message' => 'Subadmin Deleted Successfully']);
    }




    public function savePermissions(Request $request)
    {
        $subadminId = $request->subadmin_id;
        
        // Save the permissions
        foreach ($request->permissions as $menu => $perm) {
            SubAdminPermission::updateOrCreate(
                ['subadmin_id' => $subadminId, 'menu' => $menu],
                [
                    'add' => isset($perm['add']) ? 1 : 0,
                    'edit' => isset($perm['edit']) ? 1 : 0,
                    'view' => isset($perm['view']) ? 1 : 0,
                    'delete' => isset($perm['delete']) ? 1 : 0,
                ]
            );
        }
    
        return response()->json(['message' => 'Permissions updated successfully']);
    }
    
//     public function getSubadminPermission($subadminId)
// {
//     $subadmin = Subadmin::find($subadminId);

//     if (!$subadmin) {
//         return response()->json(['error' => 'Subadmin not found'], 404);
//     }

//     // Assuming permissions are stored in a JSON column
//     $permissions = json_decode($subadmin->permissions, true);

//     return response()->json(['permissions' => $permissions]);
// }

public function getPermissions(Request $request)
{
    $subadminId = $request->input('subadmin_id');

    // Fetch permissions from the database
    $permissions = DB::table('sub_admin_permissions')
                    ->where('subadmin_id', $subadminId)
                    ->get();

    return response()->json(['sub_admin_permissions' => $permissions]);
}



    public function active(Request $request, $id)
    {
        // return $request;
        // Find the user by ID
        $data = Subadmin::find($id);

        // Update the user's `is_active` status
        $data->update([
            'status' => $request->status,
        ]);

        // Prepare message data
        $message['name'] = $data->name;


        try {
            // Send an email based on `sendCredentials`

            Mail::to($data->email)->send(new SubAdminActivated($message));


            return redirect()->route('subadmin.index')->with([
                'status' => true,
                'message' => 'SubAdmin Activated Successfully',
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
        $data = Subadmin::find($id);
        // return $data;

        $data->update([
            'status' => $request->status,
        ]);

        $message['reason'] = $reason;
        $message['name'] = $data->name;

        try {
            Mail::to($data->email)->send(new SubAdminDeActivated($message));
            return redirect()->route('subadmin.index')->with(['message' => 'SubAdmin Deactivated Successfully']);
        } catch (\throwable $th) {
            dd($th->getMessage());
            return back()->with(['status' => false, 'message' => $th->getMessage()]);
        }
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }
}
