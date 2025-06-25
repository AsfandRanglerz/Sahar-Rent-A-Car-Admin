@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('subadmin.index') }}">Back</a>
                <form id="add_department" action="{{ route('subadmin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Sub Admin</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="name" placeholder="Enter Your Name" name="name"
                                                id="name" value="{{ old('name') }}" class="form-control">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email') }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="phone" placeholder="Enter Your Phone" name="phone"
                                                id="phone" value="{{ old('phone') }}" class="form-control">
                                            @error('phone')
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
                                            <label>Image (Optional)</label>
                                            <input type="file" placeholder="Enter Your Image"name="image" value="{{ old('image') }}"
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

