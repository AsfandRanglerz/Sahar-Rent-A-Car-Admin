@extends('admin.auth.layout.app')
@section('title', 'Change Password    ')
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Reset Password</h4>
                        </div>
                        <div class="card-body">
                            @if(session()->has('error_message'))
                            <p class="text-danger">The password and confirmation password do not match</p>
                            @else
                                <p class="text-muted">Enter Your New Password</p>
                            @endif
                            <form method="POST" action="{{url('admin-reset-password')}}">
                                 @csrf
                                <input value="{{$user->email}}" type="hidden" name="email" >
                                {{-- <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" tabindex="2" >
                                    <span class="fa fa-eye-slash position-absolute" style="top: 2.67rem; right:0.5rem" id="togglePassword"></span>
                                    @error('password') <span class="text-danger">{{$errors->first('password')}}</span> @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <div class="position-relative">
                                        <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" tabindex="2">
                                        <span class="fa fa-eye-slash position-absolute toggle-password" style="top: 50%; right: 10px; transform: translateY(-50%);" data-target="password"></span>
                                    </div>
                                    @error('password') <span class="text-danger">{{$errors->first('password')}}</span> @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="confirmed" tabindex="2" >
                                    @error('confirmed') <span class="text-danger">{{$errors->first('confirmed')}}</span> @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <div class="position-relative">
                                        <input id="password-confirm" type="password" class="form-control" name="confirmed" tabindex="2">
                                        <span class="fa fa-eye-slash position-absolute toggle-password" style="top: 50%; right: 10px; transform: translateY(-50%);" data-target="password-confirm"></span>
                                    </div>
                                    @error('confirmed') <span class="text-danger">{{$errors->first('confirmed')}}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Add event listener for all toggle password icons
        document.querySelectorAll('.toggle-password').forEach(function (toggleIcon) {
            toggleIcon.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const passwordField = document.getElementById(targetId);

                if (passwordField) {
                    // Toggle input type between 'password' and 'text'
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordField.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                }
            });
        });
    });
</script>

@section('js')
    @if(\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{\Illuminate\Support\Facades\Session::get('message')}}');
        
        </script>
    @endif
    
@endsection
