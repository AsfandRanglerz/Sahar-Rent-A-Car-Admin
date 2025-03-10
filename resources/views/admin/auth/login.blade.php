@extends('admin.auth.layout.app')
@section('title', 'Login')
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="Logo" style="display: block; margin: 0 auto;" class="img-fluid px-4">
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('login')}}" class="needs-validation" novalidate="">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus name="email">
                                    @error('email')
                                    <span class="text-danger">Email required</span>
                                    @enderror
                                </div>
                                <div class="form-group position-relative">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required name="password">
                                    <span class="fa fa-eye-slash position-absolute" style="top: 2.67rem; right:0.5rem" id="togglePassword"></span>
                                    @error('password')
                                    <span class="text-danger">{{$errors->first('password')}}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" tabindex="3" id="remember-me" name="remember">
                                        <div class="d-block">
                                            {{-- <label class="custom-control-label" for="remember-me">Remember Me</label> --}}
                                            <div class="float-right">
                                                <a href="{{url('admin-forgot-password')}}" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-login" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>
                            {{--<div class="text-center mt-4 mb-3">
                                <div class="text-job text-muted">Login With Social</div>
                            </div>
                            <div class="row sm-gutters">
                                <div class="col-6">
                                    <a class="btn btn-block btn-social btn-facebook">
                                        <span class="fab fa-facebook"></span> Facebook
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-block btn-social btn-twitter">
                                        <span class="fab fa-twitter"></span> Twitter
                                    </a>
                                </div>
                            </div>--}}
                        </div>
                    </div>
{{--                    <div class="mt-5 text-muted text-center">--}}
{{--                        Don't have an account? <a href="{{ route('admin.register') }}">Create One</a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
        document.querySelector('.btn-login').addEventListener('click', function () {
        const eyeIcon = document.getElementById('togglePassword');

        if (eyeIcon) {
            eyeIcon.classList.add('d-none'); // Hide the eye icon when login is clicked
        }
    });
    </script>

@endsection
@section('script')
@endsection
