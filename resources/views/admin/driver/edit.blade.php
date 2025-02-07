@extends('admin.layout.app')
@section('title', 'Edit Subadmin')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('driver.index') }}">Back</a>
                <form id="edit_subadmin" action="{{ route('driver.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Driver</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Name</label>
                                            <input type="text" placeholder="Enter Your Name" name="name"
                                                id="name" value="{{ old('name', $driver->name) }}" class="form-control">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email', $driver->email) }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" placeholder="Enter Your Phone" name="phone"
                                                id="phone" value="{{ old('phone', $driver->phone) }}" class="form-control">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Availability</label>
                                            <select name="availability" class="form-control">
                                                <option value="">Select Option</option>
                                                <option value="0" {{ $driver->availability == 0 ? 'selected' : '' }}>
                                                    Availabile</option>
                                                <option value="1" {{ $driver->availability == 1 ? 'selected' : '' }}>
                                                    Not Available</option>
                                            </select>
                                            @error('availability')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6 d-flex align-items-center">
                                        <!-- Input to Upload New Image -->
                                        <div class="flex-grow-1">
                                            <div class="form-group mb-2">
                                                <label>Image (Optional)</label>
                                                <input type="file" name="image" id="image" class="form-control">
                                                <small text-muted>(Image should be of size 2MB)</small>
                                                @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                        <!-- Display Existing Image -->
                                        @if($driver->image)
                                            <div class="ms-3">
                                                <img src="{{ asset($driver->image) }}" 
                                                     alt="image" 
                                                     style="width: 80px; height: 80px; margin-left:20px;border: 1px solid #ddd;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>License</label>
                                            <input type="file" name="license" id="license" class="form-control">
                                            
                                            @if ($driver->license)
                                                <p>Current File: 
                                                    <a href="{{ asset('storage/app/public/' . $driver->license) }}" target="_blank">
                                                        View License
                                                    </a>
                                                </p>
                                            @endif
                                    
                                            @error('emirate_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg" id="submit">Update</button>
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
