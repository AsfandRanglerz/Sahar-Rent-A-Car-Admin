<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="{{ asset('public/admin/assets/img/Sahar_logo.png') }}"
                    class="header-logo" /> <span class="logo-name"></span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Dashboard</span></a>
            </li>


            {{-- @if ((auth()->guard('web')->check() && (auth()->guard('web')->user()->can('User') || auth()->guard('web')->user()->can('Driver') || auth()->guard('web')->user()->can('Subadmin'))) || auth()->guard('admin')->check())
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="users"></i>
                        <span>User Management</span>
                    </a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/user*') || request()->is('admin/driver*') || request()->is('admin/subadmin*') ? 'show' : '' }}">

                        @if ((auth()->guard('web')->check() && auth()->guard('web')->user()->can('User')) || auth()->guard('admin')->check())
                            <li class="{{ request()->is('admin/user*') ? 'active' : '' }}">
                                <a href="{{ route('user.index') }}"
                                    class="nav-link {{ request()->is('admin/user*') ? 'text-primary' : '' }}">
                                    <i data-feather="user-plus"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                        @endif

                        @if ((auth()->guard('web')->check() && auth()->guard('web')->user()->can('Driver')) || auth()->guard('admin')->check())
                            <li class="{{ request()->is('admin/driver*') ? 'active' : '' }}">
                                <a href="{{ route('driver.index') }}"
                                    class="nav-link {{ request()->is('admin/driver*') ? 'text-white' : '' }}">
                                    <i data-feather="user"></i>
                                    <span>Drivers</span>
                                </a>
                            </li>
                        @endif

                        @if ((auth()->guard('web')->check() && auth()->guard('web')->user()->can('Subadmin')) || auth()->guard('admin')->check())
                            <li class="{{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link {{ request()->is('admin/subadmin*') ? 'text-white' : '' }}">
                                    <i data-feather="user-check"></i>
                                    <span>Sub Admins</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif --}}

            {{-- sub admin --}}
            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                <a href="{{ route('subadmin.index') }}" class="nav-link"><span><i
                            data-feather="shield"></i>Sub Admins</span></a>
            </li>

            {{-- User --}}
            <li class="dropdown {{ request()->is('admin/user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link"><span><i data-feather="user"></i>Customers</span></a>
            </li>


            {{-- drivers --}}
            <li class="dropdown {{ request()->is('admin/driver*') ? 'active' : '' }}">
                <a href="{{ route('driver.index') }}" class="nav-link"><span><i
                            data-feather="truck"></i>Drivers</span></a>
            </li>



            <li class="dropdown {{ request()->is('admin/Privacy-policy') ? 'active' : '' }}">
                <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Privacy Policy</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/term-condition') ? 'active' : '' }}">
                <a href="{{ url('/admin/term-condition') }}" class="nav-link"><i
                        data-feather="clipboard"></i><span>Terms & Conditions</span></a>
            </li>
        </ul>
    </aside>
</div>
