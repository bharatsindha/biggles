@extends('layouts.login')
@section('content')
    <!-- Login-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h2 class="card-title fw-bold mb-1">Welcome to Biggles!👋</h2>
            {{--<p class="card-text mb-2">Please sign-in to your account and start the adventure</p>--}}
            <form class="auth-login-form mt-2" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email"
                           type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com"
                           aria-describedby="email" autofocus="" tabindex="1" required autocomplete="email"/>
                    @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="#"><small>Forgot Password?</small></a>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge @error('password') is-invalid @enderror"
                               id="password" type="password" name="password" placeholder="············"
                               aria-describedby="password" tabindex="2" required autocomplete="current-password"/>
                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>

                        @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-check">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" tabindex="3"
                            {{ old('remember') ? 'checked' : '' }}/>
                        <label class="form-check-label" for="remember"> Remember Me</label>
                    </div>
                </div>
                <button class="btn btn-primary w-100" tabindex="4">{{ __('Login') }}</button>
            </form>
        </div>
    </div>
    <!-- /Login-->
@endsection
