<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminOrSubadminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $menuKey)
    {
        if (Auth::guard('admin')->check()) {
            return $next($request); // Admins can access everything
        }
        
        $subadmin = Auth::guard('subadmin')->user();

        if (!$subadmin) {
            abort(403, 'Unauthorized access.');
        }

       

        // If the user is a subadmin, check permissions
        $permissions = DB::table('sub_admin_permissions')
            ->where('subadmin_id', $subadmin->id)
            ->get()
            ->keyBy('menu')
            ->toArray();

        if (!isset($permissions[$menuKey]) || !$permissions[$menuKey]->view) {
            abort(403, 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
