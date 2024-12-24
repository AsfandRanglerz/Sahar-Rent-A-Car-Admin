@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row mb-3">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0">Total Vehicles</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img">
                                           {{-- <img src="{{ asset('public/admin/assets/img/banner/1.png')}}" alt=""> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0"> Total Bookings</h5>

                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img">
                                            {{-- <img src="{{ asset('public/admin/assets/img/banner/2.png')}}" alt=""> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0">
                                        <div class="card-content">
                                            <h5 class="font-15 mb-0">Total Active Bookings</h5>

                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                        <div class="banner-img">
                                            {{-- <img src="{{ asset('public/admin/assets/img/banner/3.png')}}" alt=""> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

