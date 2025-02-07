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

        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $remember_me=($request->remember_me)?true:false;
        if(!auth()->guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$remember_me)){
            return back()->with(['error' => 'Invalid email or password']);
        }

        // if (!auth()->guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
        //     $check = Subadmin::where('email',$request->email)->first();
        //     if ($check->status == 0) {
        //         return back()->with('warning', 'Your Account is Blocked by Admin!');
        //     }
        //     $user = auth()->guard('web')->user();
        //     if ($user->type === 'subadmin') {
        //         $request->session()->regenerate();
        //         return redirect('admin/dashboard')->with('success', 'Login Successfully as Sub-Admin');
        //     }

        //     // Logout if the user is not a sub-admin
        //     auth()->guard('web')->logout();
        //     return back()->with('warning', 'Access denied!');
        // }
        return redirect('admin/dashboard')->with(['message' => 'Login Successfully']);
    }
}
