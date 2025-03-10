@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row mb-3">
                {{-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0">Cars</h5>
                                            <h2 class="mb-0">{{ $carsCount }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img"> --}}
                                           {{-- <img src="{{ asset('public/admin/assets/img/banner/1.png')}}" alt=""> --}}
                                        {{-- </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @php
    $hasCustomerPermission = $isAdmin || (isset($subadminPermissions) && in_array('customers', array_column($subadminPermissions, 'menu')));
@endphp
                <div class="col-xl-3 mb-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a style="text-decoration: none; {{ !$hasCustomerPermission ? 'pointer-events: none;' : '' }}" @if($hasCustomerPermission) href="{{ route('user.index') }}" @endif>
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15"> Customers</h5>
                                                <h2 class="mb-3 font-18">{{ $customersCount  }}</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/Admin Icons_Customers.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{--<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0"> Customers</h5>
                                            <h2 class="mb-0">{{ $customersCount }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img">
                                            {{-- <img src="{{ asset('public/admin/assets/img/banner/2.png')}}" alt=""> --}}
                                       {{-- </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
                @php
                $hasDriverPermission = $isAdmin || (isset($subadminPermissions) && in_array('drivers', array_column($subadminPermissions, 'menu')));
            @endphp
                <div class="col-xl-3 mb-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a style="text-decoration: none; {{ !$hasDriverPermission ? 'pointer-events: none;' : '' }}" @if($hasDriverPermission) href="{{ route('driver.index') }}" @endif>
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15">Drivers</h5>
                                                <h2 class="mb-3 font-18">{{ $driversCount }}</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/Admin Icons_Drivers.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0"> Drivers</h5>
                                            <h2 class="mb-0">{{ $driversCount }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img"> --}}
                                            {{-- <img src="{{ asset('public/admin/assets/img/banner/2.png')}}" alt=""> --}}
                                        {{-- </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @php
                $hasCarPermission = $isAdmin || (isset($subadminPermissions) && in_array('cars_inventory', array_column($subadminPermissions, 'menu')));
            @endphp
                <div class="col-xl-3 mb-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a style="text-decoration: none; {{ !$hasCarPermission ? 'pointer-events: none;' : '' }}" @if($hasCarPermission) href="{{ route('car.index') }}"@endif>
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15">Cars</h5>
                                                <h2 class="mb-3 font-18">{{ $carsCount }}</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/Admin Icons_Cars.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0">Bookings</h5>
                                            <h2 class="mb-0">{{ $bookingsCount }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img"> --}}
                                            {{-- <img src="{{ asset('public/admin/assets/img/banner/3.png')}}" alt=""> --}}
                                        {{-- </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @php
                $hasBookingPermission = $isAdmin || (isset($subadminPermissions) && in_array('bookings', array_column($subadminPermissions, 'menu')));
            @endphp
                <div class="col-xl-3 mb-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a style="text-decoration: none; {{ !$hasBookingPermission ? 'pointer-events:none' : '' }}" @if($hasBookingPermission)href="{{ route('booking.index') }}"@endif>
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15">Bookings</h5>
                                                <h2 class="mb-3 font-18">{{ $totalBookingsCount}}</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/Admin Icons_Cars Bookings.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                
                {{-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0">Active Bookings</h5>
                                            <h2 class="mb-0">{{ $activeBookingsCount }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img">
                                            {{-- <img src="{{ asset('public/admin/assets/img/banner/3.png')}}" alt=""> --}}
                                        {{--</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @php
                $hasActiveBookingPermission = $isAdmin || (isset($subadminPermissions) && in_array('bookings', array_column($subadminPermissions, 'menu')));
            @endphp
                <div class="col-xl-3 mb-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a style="text-decoration: none; {{ !$hasActiveBookingPermission ? 'pointer-events:none' : '' }}" @if($hasActiveBookingPermission)href="#"@endif>
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15">Active Bookings</h5>
                                                <h2 class="mb-3 font-18">{{ $activeBookingCount }}</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/Admin Icons_ActiveBookings.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
