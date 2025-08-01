<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function getLoginPage()
    {
        return view('admin.auth.login');
    }
    public function Login(Request $request)
    {

    //     $request->validate([
    //         'email'=>'required',
    //         'password'=>'required',
    //     ]);
    //     $remember_me=($request->remember_me)?true:false;
    //     if(!auth()->guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$remember_me)){
    //         return back()->with(['error' => 'Invalid email or password']);
    //     }

    //     if (!auth()->guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
    //         $check = Subadmin::where('email',$request->email)->first();
    //         if ($check->status == 0) {
    //             return back()->with('warning', 'Your Account is Blocked by Admin!');
    //         }
    //         $user = auth()->guard('web')->user();
    //         if ($user->type === 'subadmin') {
    //             $request->session()->regenerate();
    //             return redirect('admin/dashboard')->with('success', 'Login Successfully as Sub-Admin');
    //         }

    //         // Logout if the user is not a sub-admin
    //         auth()->guard('web')->logout();
    //         return back()->with('warning', 'Access denied!');
    //     }
    //     return redirect('admin/dashboard')->with(['message' => 'Login Successfully']);
    // }

   
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $remember_me = $request->remember_me ? true : false;

    // Attempt Admin Login
    if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
        auth()->guard('subadmin')->logout(); // logout other guard if active
            $request->session()->regenerate();
        return redirect('admin/dashboard')->with(['message'=> 'Login Successfully']);
        
    }

    // Check if user exists in the Subadmin table
    $subadmin = Subadmin::where('email', $request->email)->first();

    if ($subadmin) {
        // Check if the subadmin is blocked
        if ($subadmin->status == 0) {
            return back()->with('warning', 'Your Account is Blocked by Admin!');
        }

        // Attempt Subadmin Login
        if (auth()->guard('subadmin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
            // $request->session()->regenerate();
            auth()->guard('admin')->logout(); // logout other guard if active
            $request->session()->regenerate();
            return redirect('admin/dashboard')->with(['message' => 'Login Successfully']);
        }
    }

    return back()->with('error', 'Invalid email or password');
}

}
