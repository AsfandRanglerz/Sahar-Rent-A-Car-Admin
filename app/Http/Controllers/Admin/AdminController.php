<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    //
    public function getdashboard()
    {

        return view('admin.index');
    }
    public function getProfile()
    {
        $data = Admin::find(Auth::guard('admin')->id());
        // return $data;
        return view('admin.auth.profile', compact('data'));
    }
    // public function update_profile(Request $request)
    // {
    //     return $request;
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required',
    //         'phone' => 'required'
    //     ]);
    //     $data = $request->only(['name', 'email', 'phone']);
    //     if ($request->hasfile('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension(); // getting image extension
    //         $filename = time() . '.' . $extension;
    //         $file->move(public_path('/'), $filename);
    //         $data['image'] = 'public/uploads/' . $filename;
    //     }
    //     Admin::find(Auth::guard('admin')->id())->update($data);
    //     return back()->with([ 'message' => 'Profile Updated Successfully']);
    // }

    public function update_profile(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                ]);

        // Get the logged-in admin ID
        $admin = Admin::find(Auth::guard('admin')->id());

        // Data to be updated
        $data = $request->only(['name', 'email', 'phone']);

        // Handle image upload if a file is present
        if ($request->hasFile('image')) {
            // Delete the previous image if it exists
            if ($admin->image && file_exists(public_path($admin->image))) {
                unlink(public_path($admin->image));
            }

            // Upload the new image
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);

            // Set the new image path
            $data['image'] = 'public/admin/assets/images/users/' . $filename;
        }

        // Update the admin profile
        $admin->update($data);

        // Redirect back with a success message
        return back()->with(['message' => 'Profile Updated Successfully']);
    }

    public function forgetPassword()
    {
        return view('admin.auth.forgetPassword');
    }
    public function adminResetPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:admins,email',
        ]);
        $exists = DB::table('password_resets')->where('email', $request->email)->first();
        if ($exists) {
            return back()->with('message', 'Reset Password link has been already sent');
        } else {
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);

            $data['url'] = url('change_password', $token);
            Mail::to($request->email)->send(new ResetPasswordMail($data));
            return back()->with('message', 'Reset Password Link Send Successfully');
        }
    }
    public function change_password($id)
    {

        $user = DB::table('password_resets')->where('token', $id)->first();

        if (isset($user)) {
            return view('admin.auth.chnagePassword', compact('user'));
        }
    }

    public function resetPassword(Request $request)
    {

        $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required',

        ]);
        if ($request->password != $request->confirmed) {

            return back()->with(['error_message' => 'Password not matched']);
        }
        $password = bcrypt($request->password);
        $tags_data = [
            'password' => bcrypt($request->password)
        ];
        if (Admin::where('email', $request->email)->update($tags_data)) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('admin')->with('message', 'Password reset successfully');
        }
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('admin')->with(['error' => 'Logout Successfully']);
    }
}
