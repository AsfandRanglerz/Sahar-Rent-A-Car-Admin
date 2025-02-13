<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i
                        data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
       
        <?php
         // use App\Models\Admin;
        // use App\Models\Subadmin;
        // $admin = Auth::guard('admin')->check() ? Admin::find(Auth::guard('admin')->id()) : null;
        // $Subadmin = Auth::guard('subadmin')->check() ? Subadmin::find(Auth::guard('subadmin')->id()) : null;
        use App\Models\Admin;
        use App\Models\Subadmin;
        
        // Get logged-in admin or subadmin
        $admin = Auth::guard('admin')->user();
        $subadmin = Auth::guard('subadmin')->user();
        
        // Determine user and profile image
        $user = $admin ?? $subadmin;
        $image = $user && $user->image ? asset($user->image) : asset('public/admin/assets/img/user.png');
        ?>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                {{-- @if ($admin && $admin->image) --}}
                    <img alt="image" src="{{ $image }}" class="user-img-radious-style">
                {{-- @else
                    <img alt="image" src="{{ asset('public/admin/assets/img/user.png') }}"
                        class="user-img-radious-style">
                @endif --}}
                {{-- <img
                    alt="image" src="{{ asset('public/admin/assets/img/user.png') }}" class="user-img-radious-style"> --}}
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">Hello {{ $admin->name ?? 'Admin' }}</div>
                <a href="{{ url('admin/profile') }}" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('admin/logout') }}" class="dropdown-item has-icon text-danger"> <i
                        class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
