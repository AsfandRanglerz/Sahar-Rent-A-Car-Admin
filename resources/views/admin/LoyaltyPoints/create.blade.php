@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('loyaltypoints.index') }}">Back</a>
                <form id="add_department" action="{{ route('loyaltypoints.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Loyalty Point</h4>
                                <div class="row mx-0 px-4">
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label for="booking_id">Select Car:</label>
                                        <select name="booking_id" id="booking_id" class="form-control" required>
                                            <option value="">-- Select Car --</option>
                                            @foreach($bookings as $booking)
                                                <option value="{{ $booking->id }}"> {{ $booking->car->car_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div> --}}
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="car_id">Select Car:</label>
                                            <select name="car_id" id="car_id" class="form-control" required>
                                                <option value="">-- Select Car --</option>
                                                @foreach($cars as $car)
                                                    <option value="{{ $car->id }}">{{ $car->car_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>On Referal Link</label>
                                            <input type="number" placeholder="Points i.e 5" name="on_referal"
                                                id="on_referal" value="{{ old('on_referal') }}" class="form-control">
                                            @error('on_referal')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email') }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>On Car Rental</label>
                                            <input type="number" placeholder="Points i.e 5" name="on_car"
                                                id="on_car" value="{{ old('on_car') }}" class="form-control">
                                            @error('on_car')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Address</label>
                                            <input type="text" placeholder="Enter Address" name="address"
                                                id="address" value="{{ old('address') }}" class="form-control">
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Discount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">Loyalty Points</span>
                                                </div>
                                                <input type="number" placeholder="Enter Discount %" name="discount"
                                                    id="discount" value="{{ old('discount') }}" class="form-control">
                                            </div>
                                            <small class="text-muted">(Note: Enter the discount percentage based on loyalty points)</small>
                                            @error('discount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Image (Optional)</label>
                                            <input type="file" placeholder="Enter Your Image"name="image" value="{{ old('image') }}"
                                                class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection

