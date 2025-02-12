<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/img/Sahar_logo.png') }}"
                    class="header-logo" /> <span class="logo-name"></span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            @php
    // Check if the user is an admin
    $isAdmin = Auth::guard('admin')->check();
    $subadminPermissions = $subadminPermissions ?? [];
@endphp
            @if($isAdmin || in_array('dashboard', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
@endif

            
            {{-- sub admin --}}
            @if($isAdmin || in_array('sub_admins', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                <a href="{{ route('subadmin.index') }}" class="nav-link"><span><i
                            data-feather="shield"></i>Sub Admins</span></a>
            </li>
@endif
            {{-- User --}}
            @if($isAdmin || in_array('customers', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link"><span><i data-feather="users"></i>Customers</span></a>
            </li>
@endif

            {{-- drivers --}}
            @if($isAdmin || in_array('drivers', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/driver*') ? 'active' : '' }}">
                <a href="{{ route('driver.index') }}" class="nav-link"><span><i
                            data-feather="truck"></i>Drivers</span></a>
            </li>
@endif
            {{-- car fleet --}}
            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="layout"></i> <!-- Changed to 'layout' for header section -->
                    <span>Car Fleet</span>
                </a> --}}
                {{-- <ul class="dropdown-menu {{ request()->is('admin/car*') || request()->is('admin/PlaceOrderTwo*') || request()->is('admin/PlaceOrderThree*') || request()->is('admin/PlaceOrderFour*')  ? 'show' : '' }}"> --}}

                    @if($isAdmin || in_array('cars_inventory', $subadminPermissions))
                    <li class="dropdown {{ request()->is('admin/car*') ? 'active' : '' }}">
                <a href="{{ route('car.index') }}" class="nav-link "><span><i
                            data-feather="truck"></i>Cars Inventory</span></a>
            </li>
            @endif
                {{-- </ul> --}}
            {{-- </li> {{ request()->is('admin/car*') ? 'text-white' : '' }} --}}

            @if($isAdmin || in_array('license_approvals', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/license*') ? 'active' : '' }}">
                <a href="{{ route('license.index') }}" class="nav-link"><span ><i
                            data-feather="file-text"></i>License Approvals
                            @if (isset($pendingCount) && $pendingCount > 0)
                            <span class="badge rounded-pill bg-warning text-dark d-flex justify-content-center align-items-center" style="width: 24px; height: 24px; font-size: 14px;">{{ $pendingCount }}</span>
                        @endif
                        </span></a>
            </li>
@endif
            @if($isAdmin || in_array('notifications', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/notification*') ? 'active' : '' }}">
                <a href="{{ route('notification.index') }}" class="nav-link"><span><i
                            data-feather="bell"></i>Notifications</span></a>
            </li>
@endif

            @if($isAdmin || in_array('bookings', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/booking*') ? 'active' : '' }}">
                <a href="{{ route('booking.index') }}" class="nav-link"><span><i
                            data-feather="calendar"></i>Bookings</span></a>
            </li>
@endif
            @if($isAdmin || in_array('loyalty_points', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/loyaltypoints*') ? 'active' : '' }}">
                <a href="{{ route('loyaltypoints.index') }}" class="nav-link"><span><i
                            data-feather="gift"></i>Loyalty Points</span></a>
            </li>
@endif

            @if($isAdmin || in_array('privacy_policy', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/Privacy-policy*') || request()->is('admin/privacy-policy-edit*') ? 'active' : '' }}">
                <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Privacy Policy</span></a>
            </li>
            @endif
            @if($isAdmin || in_array('terms_conditions', $subadminPermissions))
            <li class="dropdown {{ request()->is('admin/term-condition*') || request()->is('admin/term-condition-edit*') ? 'active' : '' }}">
                <a href="{{ url('/admin/term-condition') }}" class="nav-link"><i
                        data-feather="clipboard"></i><span>Terms & Conditions</span></a>
            </li>
@endif
        </ul>
    </aside>
</div>


{{-- <div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/img/Sahar_logo.png') }}"
                    class="header-logo" /> <span class="logo-name"></span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            @php
    // Check if the user is an admin
    $isAdmin = Auth::guard('admin')->check();
    $subadminPermissions = $subadminPermissions ?? [];
@endphp
    @if($isAdmin || isset($subadminPermissions['dashboard']))
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
@endif

            
            {{-- sub admin --}}
            {{-- @if($isAdmin || isset($subadminPermissions['sub_admins']))
            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                <a href="{{ route('subadmin.index') }}" class="nav-link"><span><i
                            data-feather="shield"></i>Sub Admins</span></a>
            </li>
@endif --}}
            {{-- User --}}
            {{-- @if($isAdmin || isset($subadminPermissions['customers']))
            <li class="dropdown {{ request()->is('admin/user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link"><span><i data-feather="users"></i>Customers</span></a>
            </li>
@endif --}}

            {{-- drivers --}}
            {{-- @if($isAdmin || isset($subadminPermissions['drivers']))
            <li class="dropdown {{ request()->is('admin/driver*') ? 'active' : '' }}">
                <a href="{{ route('driver.index') }}" class="nav-link"><span><i
                            data-feather="truck"></i>Drivers</span></a>
            </li>
@endif --}}
            {{-- car fleet --}}
            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="layout"></i> <!-- Changed to 'layout' for header section -->
                    <span>Car Fleet</span>
                </a> --}}
                {{-- <ul class="dropdown-menu {{ request()->is('admin/car*') || request()->is('admin/PlaceOrderTwo*') || request()->is('admin/PlaceOrderThree*') || request()->is('admin/PlaceOrderFour*')  ? 'show' : '' }}"> --}}

                    {{-- @if($isAdmin || isset($subadminPermissions['cars_inventory']))
                    <li class="dropdown {{ request()->is('admin/car*') ? 'active' : '' }}">
                <a href="{{ route('car.index') }}" class="nav-link "><span><i
                            data-feather="truck"></i>Cars Inventory</span></a>
            </li>
            @endif --}}
                {{-- </ul> --}}
            {{-- </li> {{ request()->is('admin/car*') ? 'text-white' : '' }} --}}

            {{-- @if($isAdmin || isset($subadminPermissions['license_approvals']))
            <li class="dropdown {{ request()->is('admin/license*') ? 'active' : '' }}">
                <a href="{{ route('license.index') }}" class="nav-link"><span ><i
                            data-feather="file-text"></i>License Approvals
                            @if (isset($pendingCount) && $pendingCount > 0)
                            <span class="badge rounded-pill bg-warning text-dark d-flex justify-content-center align-items-center" style="width: 24px; height: 24px; font-size: 14px;">{{ $pendingCount }}</span>
                        @endif
                        </span></a>
            </li>
@endif
@if($isAdmin || isset($subadminPermissions['notifications']))
            <li class="dropdown {{ request()->is('admin/notification*') ? 'active' : '' }}">
                <a href="{{ route('notification.index') }}" class="nav-link"><span><i
                            data-feather="bell"></i>Notifications</span></a>
            </li>
@endif

@if($isAdmin || isset($subadminPermissions['bookings']))
            <li class="dropdown {{ request()->is('admin/booking*') ? 'active' : '' }}">
                <a href="{{ route('booking.index') }}" class="nav-link"><span><i
                            data-feather="calendar"></i>Bookings</span></a>
            </li>
@endif
@if($isAdmin || isset($subadminPermissions['loyalty_points']))
            <li class="dropdown {{ request()->is('admin/loyaltypoints*') ? 'active' : '' }}">
                <a href="{{ route('loyaltypoints.index') }}" class="nav-link"><span><i
                            data-feather="gift"></i>Loyalty Points</span></a>
            </li>
@endif

@if($isAdmin || isset($subadminPermissions['privacy_policy']))
            <li class="dropdown {{ request()->is('admin/Privacy-policy*') || request()->is('admin/privacy-policy-edit*') ? 'active' : '' }}">
                <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Privacy Policy</span></a>
            </li>
            @endif
            @if($isAdmin || isset($subadminPermissions['terms_conditions']))
            <li class="dropdown {{ request()->is('admin/term-condition*') || request()->is('admin/term-condition-edit*') ? 'active' : '' }}">
                <a href="{{ url('/admin/term-condition') }}" class="nav-link"><i
                        data-feather="clipboard"></i><span>Terms & Conditions</span></a>
            </li>
@endif
        </ul>
    </aside>
</div> --}} 
