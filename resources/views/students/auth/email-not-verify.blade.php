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
                    <h2 class="font-title--md mb-0">Email Verification</h2>                    
                    <form action="{{ route('resend-verification') }}" method="POST">
          @csrf
            <div>
              <div>                
                <h3>Please verify your email</h3>                
                <p>Your email address <strong>({{ auth()->user()->email }})</strong> has not been verified, click on the button below to send email verification link.</p>
              </div> 
              <div class="button input-box">
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                <input type="submit" value="Verify Email">
              </div>              
            </div>
        </form>
                </div>
            </div>
            <div class="col-xl-7 order-1 order-xl-0">
                <div class="signup-area-image">
                    <img src="{{asset('frontend/dist/images/sign/img-3.jpg')}}" alt="Illustration Image"
                        class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SignIn Area Ends Here -->
@endsection