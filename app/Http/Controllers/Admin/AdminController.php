<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Subadmin;
use App\Models\CarDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RequestBooking;
use App\Mail\ResetPasswordMail;
use App\Models\DriverNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    //
    public function getdashboard()
    {
        $customersCount = User::count();
        $carsCount = CarDetails::count();
        $driversCount = Driver::count();
        $bookingsCount = Booking::count();
        $requestBookingsCount = RequestBooking::count();
        $totalBookingsCount = $bookingsCount + $requestBookingsCount;
        $activeBookingsCount = Booking::where('status', 0)->count();
        $activerequestBookingsCount = RequestBooking::where('status', 0)->count();
        $activeBookingCount = $activeBookingsCount + $activerequestBookingsCount;
        return view('admin.index', compact('customersCount','carsCount', 'driversCount', 'totalBookingsCount', 'activeBookingCount'));
    }
    public function getProfile()
    {
        $admin = Admin::find(Auth::guard('admin')->id());
        $Subadmin = Subadmin::find(Auth::guard('subadmin')->id());

        $data = $admin ?? $Subadmin;
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
            // 'phone' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                ]);

        // Get the logged-in admin ID
        $admin = Admin::find(Auth::guard('admin')->id());
        $Subadmin = Subadmin::find(Auth::guard('subadmin')->id());

        $user = $admin ?? $Subadmin;
        
        // Data to be updated
        $data = $request->only(['name', 'email']);

        // Handle image upload if a file is present
        if ($request->hasFile('image')) {
            // Delete the previous image if it exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
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
        $user->update($data);

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
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $admin = DB::table('admins')->where('email', $value)->exists();
                    $subadmin = DB::table('subadmins')->where('email', $value)->exists();
                    if (!$admin && !$subadmin) {
                        $fail('The selected email is invalid.');
                    }
                },
            ],
        ]);
        
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);

            $data['url'] = url('change_password', $token);
            Mail::to($request->email)->send(new ResetPasswordMail($data));
            return back()->with('message', 'Reset Password Link Send Successfully');
        
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
        if (Admin::where('email', $request->email)->exists()) {
            Admin::where('email', $request->email)->update($tags_data);
        } 
        // Check if the email exists in Subadmins
        elseif (Subadmin::where('email', $request->email)->exists()) {
            Subadmin::where('email', $request->email)->update($tags_data);
        } 
        else {
            return back()->with(['error_message' => 'Email not found.']);
        }
    
        // Delete the reset token
        DB::table('password_resets')->where('email', $request->email)->delete();
    
        // Redirect to login page with success message
        return redirect('admin')->with('message', 'Password reset successfully');
        
        // if (Admin::where('email', $request->email)->update($tags_data)) {
        //     DB::table('password_resets')->where('email', $request->email)->delete();
        //     return redirect('admin')->with('message', 'Password reset successfully');
        // }
        

    }
    public function logout()
    {
        // Auth::guard('admin')->logout();
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('subadmin')->check()) {
            Auth::guard('subadmin')->logout();
        }  
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('admin')->with(['message' => 'Logout Successfully']);
    }

    public function getNotifications()
    {
        $notifications = DriverNotification::where('is_read', 0)->orderBy('created_at', 'desc')->get();
        return response()->json(['notifications' => $notifications]);
    }
    
    public function markNotificationsRead(Request $request)
    {
        $notification = DriverNotification::find($request->id);
    
    if ($notification) {
        $notification->is_read = 1;
        $notification->save();
    }  //0 means false and 1 means true
        return response()->json(['status' => 'success']);
    }
    
    public function markAllNotificationsRead()
{
    DriverNotification::where('is_read', 0)->update(['is_read' => 1]);

    return response()->json(['status' => 'success']);
}

}
