@extends('admin.layout.app')
@section('title', 'Edit Subadmin')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('user.index')}}">Back</a>
                <form id="edit_subadmin" action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Customer</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Name</label>
                                            <input type="text" placeholder="Enter Your Name" name="name"
                                                id="name" value="{{ old('name', $user->name) }}" class="form-control">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email', $user->email) }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" placeholder="Enter Your Phone" name="phone"
                                                id="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Address</label>
                                            <input type="text" placeholder="Enter Address" name="address"
                                                id="address" value="{{ old('address', $user->address) }}" class="form-control">
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3 d-none">
                                        <div class="form-group mb-2">
                                            <label>Password</label>
                                            <input type="password" placeholder="Enter Your Password" name="password"
                                                id="password" value="{{ old('password',$user->password) }}" class="form-control">
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

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
                                        @if($user->image)
                                            <div class="ms-3">
                                                <img src="{{ asset($user->image) }}" 
                                                     alt="image" 
                                                     style="width: 80px; height: 80px; margin-left:20px;border: 1px solid #ddd;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Emirate Id</label>
                                            <input type="file" name="emirate_id" id="emirate_id" class="form-control">
                                            
                                            @if ($user->emirate_id)
                                                <p>Current File: 
                                                    <a href="{{ asset( $user->emirate_id) }}" target="_blank">
                                                        View Emirate ID
                                                    </a>
                                                </p>
                                            @endif
                                    
                                            @error('emirate_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Passport</label>
                                            <input type="file" name="passport" id="passport" class="form-control">
                                            
                                            @if ($user->passport)
                                                <p>Current File: 
                                                    <a href="{{ asset( $user->passport) }}" target="_blank">
                                                        View Passport
                                                    </a>
                                                </p>
                                            @endif
                                    
                                            @error('passport')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Driving License</label>
                                            <input type="file" name="driving_license" id="driving_license" class="form-control">
                                            
                                            @if ($user->driving_license)
                                                <p>Current File: 
                                                    <a href="{{ asset( $user->driving_license) }}" target="_blank">
                                                        View Driving License
                                                    </a>
                                                </p>
                                            @endif
                                    
                                            @error('driving_license')
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
