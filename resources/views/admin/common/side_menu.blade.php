<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}"
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
            @if ($isAdmin || isset($subadminPermissions['dashboard']))
                <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>
            @endif

            @if ($isAdmin || isset($subadminPermissions['sub_admins']) || isset($subadminPermissions['admin_logs']))
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="layout"></i> <!-- Icon for header section -->
                        <span>Sub Admins</span>
                    </a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/subadmin*') || request()->is('admin/logs*') ? 'show' : '' }}">

                        {{-- Sub Admins --}}
                        @if ($isAdmin || isset($subadminPermissions['sub_admins']))
                            <li class="{{ request()->is('admin/subadmin*') }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link  {{ request()->is('admin/subadmin*') ? 'active bg-primary text-white' : '' }}">
                                    <i data-feather="shield"></i>
                                    <span>Sub Admins</span>
                                </a>
                            </li>
                        @endif

                        {{-- Admin Logs --}}
                        @if ($isAdmin || isset($subadminPermissions['admin_logs']))
                            <li class="{{ request()->is('admin/logs*') }}">
                                <a href="{{ route('admin.logs') }}"
                                    class="nav-link {{ request()->is('admin/logs*') ? 'active bg-primary text-white' : '' }}">
                                    <i data-feather="file-text"></i>
                                    <span>SubAdmin Logs</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
            {{-- User --}}
            {{-- @if ($isAdmin || isset($subadminPermissions['customers'])) --}}
            @if ($isAdmin || (isset($subadminPermissions['customers']) && $subadminPermissions['customers']->view == 1))
                <li class="dropdown {{ request()->is('admin/user*') ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link"><i
                                data-feather="users"></i><span>Customers</span></a>
                </li>
            @endif

            {{-- drivers --}}
            {{-- @if ($isAdmin || isset($subadminPermissions['drivers'])) --}}
            @if ($isAdmin || (isset($subadminPermissions['drivers']) && $subadminPermissions['drivers']->view == 1))
                <li class="dropdown {{ request()->is('admin/driver*') ? 'active' : '' }}">
                    <a href="{{ route('driver.index') }}" class="nav-link"><i
                                data-feather="truck"></i><span>Drivers</span></a>
                </li>
            @endif

            @if ($isAdmin || (isset($subadminPermissions['cars_inventory']) && $subadminPermissions['cars_inventory']->view == 1))
                <li class="dropdown {{ request()->is('admin/car*') ? 'active' : '' }}">
                    <a href="{{ route('car.index') }}" class="nav-link "><i data-feather="truck"></i><span>Cars
                            Inventory</span></a>
                </li>
            @endif

            @if (
                $isAdmin ||
                    (isset($subadminPermissions['license_approvals']) && $subadminPermissions['license_approvals']->view == 1))
                <li class="dropdown {{ request()->is('admin/license*') ? 'active' : '' }}">
                    <a href="{{ route('license.index') }}" class="nav-link">
                            <i data-feather="file-text"></i><span>License Approvals</span>
                        <div id="updatelicenseCounter"
                            class="badge {{ request()->is('admin/license*') ? 'bg-white text-dark' : 'bg-primary text-white' }} rounded-circle"
                            style="display: inline-flex; justify-content: center; align-items: center; 
                min-width: 22px; height: 22px; border-radius: 50%; 
                text-align: center; font-size: 12px; margin-left: 5px; padding: 3px;">
                            0
                        </div>
                    </a>
                    {{-- @if (isset($pendingCount) && $pendingCount > 0) --}}
                </li>
            @endif
            {{-- @if ($isAdmin || isset($subadminPermissions['notifications'])) --}}

            {{-- @if ($isAdmin || isset($subadminPermissions['bookings'])) --}}
            @if ($isAdmin || (isset($subadminPermissions['bookings']) && $subadminPermissions['bookings']->view == 1))
                <li class="dropdown {{ request()->is('admin/booking*') ? 'active' : '' }}">
                    <a href="{{ route('booking.index') }}" class="nav-link"><i
                                data-feather="calendar"></i><span>Bookings</span>
                        <div id="bookingCounter" class="badge {{ request()->is('admin/booking*') ? 'bg-white text-dark' : 'bg-success text-white' }}" style="display: inline-flex; justify-content: center; align-items: center; min-width: 22px; height: 22px; border-radius: 50%; text-align: center; font-size: 12px; margin-left: 5px; padding: 3px;">0</div>
                    </a>
                </li>
            @endif
            @if ($isAdmin || isset($subadminPermissions['requestbookings']) || isset($subadminPermissions['requestbookings']))
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="calendar"></i> <!-- Icon for header section -->
                        <span>Request Bookings</span>
                        <div class="badge {{ request()->is('admin/requestbookings*') ? 'bg-white text-dark' : 'bg-primary text-white' }}"
                        style="display: inline-flex; justify-content: center; align-items: center; 
                        min-width: 22px; height: 22px; border-radius: 50%; 
                        text-align: center; font-size: 12px; margin-left: 5px; margin-right:10px; padding: 3px;">
                        {{ $totalCount }}
                        </div>
                    </a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/requestbooking*') || request()->is('admin/dropoff*') ? 'show' : '' }}">

                        @if ($isAdmin || isset($subadminPermissions['requestbookings']))
                            <li class="{{ request()->is('admin/requestbooking*') }}">
                                <a href="{{ route('requestbooking.index') }}"
                                    class="nav-link  {{ request()->is('admin/requestbooking*') ? 'active bg-primary text-white' : '' }}">
                                    <i data-feather="calendar"></i>
                                    <span>Dropoff Requests</span>
                                    <div id="pendingRequestCounter"
                                        class="badge {{ request()->is('admin/requestbooking*') ? 'bg-white text-dark' : 'bg-primary text-white' }} rounded-circle"
                                        style="display: inline-flex; justify-content: center; align-items: center; 
            min-width: 22px; height: 22px; border-radius: 50%; 
            text-align: center; font-size: 12px; margin-left: 5px; padding: 3px;">
                                        0
                                    </div>
                                </a>
                            </li>
                        @endif

                        @if ($isAdmin || isset($subadminPermissions['requestbookings']))
                            <li class="{{ request()->is('admin/dropoff*') }}">
                                <a href="{{ route('dropoffs.index') }}"
                                    class="nav-link {{ request()->is('admin/dropoff*') ? 'active bg-primary text-white' : '' }}">
                                    <i data-feather="calendar"></i>
                                    <span>Pickup Requests</span>
                                    <div id="dropoffCounter"
                                        class="badge {{ request()->is('admin/dropoff*') ? 'bg-white text-dark' : 'bg-primary text-white' }}"
                                        style="display: inline-flex; justify-content: center; align-items: center; 
            min-width: 22px; height: 22px; border-radius: 50%; 
            text-align: center; font-size: 12px; margin-left: 5px; padding: 3px;">
                                        0
                                    </div>

                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if ($isAdmin || isset($subadminPermissions['loyalty_points']) || isset($subadminPermissions['referal_links']))
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="gift"></i> <!-- Icon for header section -->
                        <span>Loyalty Points</span>
                    </a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/loyaltypoints*') || request()->is('admin/referals*') ? 'show' : '' }}">


                        @if ($isAdmin || isset($subadminPermissions['loyalty_points']))
                            <li class="{{ request()->is('admin/loyaltypoints*') }}">
                                <a href="{{ route('loyaltypoints.index') }}"
                                    class="nav-link  {{ request()->is('admin/loyaltypoints*') ? 'active bg-primary text-white' : '' }}">
                                    <i data-feather="gift"></i>
                                    <span>Rental Reward Points</span>
                                </a>
                            </li>
                        @endif

                        {{-- Admin Logs --}}
                        @if ($isAdmin || isset($subadminPermissions['referal_links']))
                            <li class="{{ request()->is('admin/referals*') }}">
                                <a href="{{ route('referals.index') }}"
                                    class="nav-link {{ request()->is('admin/referals*') ? 'active bg-primary text-white' : '' }}">
                                    <i data-feather="gift"></i>
                                    <span>Referral Bonus Points</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
            @if (
                $isAdmin ||
                    (isset($subadminPermissions['chat']) && $subadminPermissions['chat']->view == 1))
            <li
                class="dropdown {{ request()->is('admin/chat*') || request()->is('admin/About-us-edit*') ? 'active' : '' }}">
                <a href="{{ url('/admin/chat') }}" class="nav-link"><i
                        data-feather="message-square"></i><span>Chat</span>
                    <div id="chatRequestCounter"
                        class="badge {{ request()->is('admin/chat*') ? 'bg-white text-dark' : 'bg-primary text-white' }} rounded-circle"
                        style="display: inline-flex; justify-content: center; align-items: center; 

            min-width: 22px; height: 22px; border-radius: 50%; 

            text-align: center; font-size: 12px; margin-left: 5px; padding: 3px;">

                        0

                    </div>
                </a>
            </li>
            @endif
            @if ($isAdmin || (isset($subadminPermissions['notifications']) && $subadminPermissions['notifications']->view == 1))
                <li class="dropdown {{ request()->is('admin/notification*') ? 'active' : '' }}">
                    <a href="{{ route('notification.index') }}" class="nav-link"><i
                                data-feather="bell"></i><span>Notifications</span></a>
                </li>
            @endif
            @if ($isAdmin || (isset($subadminPermissions['about_us']) && $subadminPermissions['about_us']->view == 1))
                <li
                    class="dropdown {{ request()->is('admin/About-us*') || request()->is('admin/About-us-edit*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/About-us') }}" class="nav-link"><i data-feather="info"></i><span>About
                            Us</span></a>
                </li>
            @endif

            @if ($isAdmin || (isset($subadminPermissions['ContactUs']) && $subadminPermissions['ContactUs']->view == 1))
                <li class="dropdown {{ request()->is('admin/ContactUs*') ? 'active' : '' }}">
                    <a href="{{ route('ContactUs.index') }}" class="nav-link"><i
                                data-feather="mail"></i><span>Contact Us</span></a>
                </li>
            @endif


            {{-- @if ($isAdmin || isset($subadminPermissions['privacy_policy'])) --}}
            @if ($isAdmin || (isset($subadminPermissions['privacy_policy']) && $subadminPermissions['privacy_policy']->view == 1))
                <li
                    class="dropdown {{ request()->is('admin/Privacy-policy*') || request()->is('admin/privacy-policy-edit*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Privacy Policy</span></a>
                </li>
            @endif
            {{-- @if ($isAdmin || isset($subadminPermissions['terms_conditions'])) --}}
            @if (
                $isAdmin ||
                    (isset($subadminPermissions['terms_conditions']) && $subadminPermissions['terms_conditions']->view == 1))
                <li
                    class="dropdown {{ request()->is('admin/term-condition*') || request()->is('admin/term-condition-edit*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/term-condition') }}" class="nav-link"><i
                            data-feather="clipboard"></i><span>Terms & Conditions</span></a>
                </li>
            @endif


        </ul>
    </aside>
</div>