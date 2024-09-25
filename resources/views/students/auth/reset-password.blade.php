@extends('frontend.layouts.app')
@section('title', 'Sign In')
@section('header-attr') class="nav-shadow" @endsection

@section('content')
<!-- SignIn Area Starts Here -->
<section class="signup-area signin-area p-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-5 order-2 order-xl-0">
                <div class="signup-area-textwrapper">
                    <h2 class="font-title--md mb-0">Reset Password</h2> 
                    <h4>Enter your new password, confirm and submit.</h4> 
                    @if(auth()->guest())                  
                            <form action="{{ route('password.update') }}" method="POST">
              @csrf
            <div>
                        <div   div class="form-element">
                            <label for="email">Email</label>
                            <strong><p align="center">{{ $email }}</p> </strong>
                        </div>
                        <div class="form-element">
                            <label for="password" class="w-100" style="text-align: left;">password</label>
                            <div class="form-alert-input">
                                <input type="password" placeholder="Type here..." id="password"  name="password"/>
                                <div class="form-alert-icon" onclick="showPassword('password',this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </div>
                                @if($errors->has('password'))
                                    <small class="d-block text-danger">{{$errors->first('password')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="form-element">
                            <label for="password_confirmation" class="w-100" style="text-align: left;">Confirm
                                password</label>
                            <div class="form-alert-input">
                                <input type="password" placeholder="Type here..." name="password_confirmation" id="password_confirmation" />
                                <div class="form-alert-icon" onclick="showPassword('password_confirmation',this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </div>
                            </div>
                        </div>
              <div class="button input-box">
                <input type="submit" value="{{ __('Send Password Reset Link') }}"  class="button button-lg button--primary w-100">
              </div>              
            </div>
        </form>
        @else
    <p>You are already logged in. You cannot reset your password while logged in.</p>
@endif
                </div>
            </div>
            <div class="col-xl-7 order-1 order-xl-0">
                <div class="signup-area-image">
                    <img src="{{asset('frontend/dist/images/sign/img-2.jpg')}}" alt="Illustration Image"
                        class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SignIn Area Ends Here -->
@endsection