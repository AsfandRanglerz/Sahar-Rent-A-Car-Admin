<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\AuthController;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if(Auth::guard('admin')->check() || Auth::guard('subadmin')->check()){
        if (Auth::guard('admin')->check()) {
            view()->share('isAdmin', true);
            // Admin is logged in, set an empty array to avoid undefined variable error
            view()->share('subadminPermissions', []);
            return $next($request);
        }
    
        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            if ($subadmin->status == 0) {
                Auth::guard('subadmin')->logout();
                Session::flush(); // Clear all session data
                Session::invalidate();
                Session::regenerateToken();
                return redirect('/admin')->with('error', 'Your account has been deactivated.');
            }
            if ($subadmin) {
                $permissions = DB::table('sub_admin_permissions')
                    ->where('subadmin_id', $subadmin->id)
                    // ->pluck('menu')
                    // ->toArray();
                    ->get()
                    ->keyBy('menu')
                    ->toArray();
                view()->share('isAdmin', false); 
                view()->share('subadminPermissions', $permissions);
                
            } 
            else {
                view()->share('subadminPermissions', []);
            }
    
            return $next($request);
        }
    
        return redirect('admin')->with('error', 'Unauthorized Access');
     }



    
}
