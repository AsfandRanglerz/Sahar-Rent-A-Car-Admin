@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('user.index') }}">Back</a>
                <form id="add_department" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Customer</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Name" name="name"
                                                id="name" value="{{ old('name') }}" class="form-control">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" placeholder="Enter Email" name="email"
                                                id="email"  value="{{ old('email') }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" placeholder="Enter Phone Number" name="phone"
                                                id="phone" value="{{ old('phone') }}" class="form-control">
                                            @error('phone')
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
                                        <div class="form-group mb-2 position-relative">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <input type="password" placeholder="Enter Password" name="password"
                                                id="password" value="{{ old('password') }}"  class="form-control">
                                                <span class="fa fa-eye-slash position-absolute" style="top: 2.67rem; right:0.5rem" id="togglePassword"></span>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Emirate Id (Front) <span class="text-danger">*</span></label>
                                            <input type="file" placeholder="Enter Document"name="emirate_id" value="{{ old('emirate_id') }}"
                                                class="form-control">
                                            @error('emirate_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Emirate Id (Back) <span class="text-danger">*</span></label>
                                            <input type="file" placeholder="Enter Document"name="emirate_id_back" value="{{ old('emirate_id_back') }}"
                                                class="form-control">
                                            @error('emirate_id_back')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror   
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Passport <span class="text-danger">*</span></label>
                                            <input type="file" placeholder="Enter Document"name="passport" value="{{ old('passport') }}"
                                                class="form-control">
                                            @error('passport')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Driving License (Front) <span class="text-danger">*</span></label>
                                            <input type="file" placeholder="Enter Document"name="driving_license" value="{{ old('driving_license') }}"
                                                class="form-control">
                                            @error('driving_license')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                     <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Driving License (Back) <span class="text-danger">*</span></label>
                                            <input type="file" placeholder="Enter Document"name="driving_license_back" value="{{ old('driving_license_back') }}"
                                                class="form-control">
                                            @error('driving_license_back')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Image (Optional)</label>
                                            <input type="file" placeholder="Enter Image"name="image" value="{{ old('image') }}"
                                                class="form-control">
                                                <small text-muted>(Image should be of size 2MB)</small>
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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

    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>

@endsection

