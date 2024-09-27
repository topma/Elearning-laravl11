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
                    @if(auth()->guest())                  
                            <form action="{{ route('password.email') }}" method="POST">
              @csrf
            <div>
            <div class="form-element">
                            <label for="email">Email</label>
                            <input type="email" placeholder="Username" id="email" name="email" />
                            @if($errors->has('email'))
                            <small class="d-block text-danger">{{$errors->first('email')}}</small>
                            @endif
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