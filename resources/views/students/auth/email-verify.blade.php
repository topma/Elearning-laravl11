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
                            <form action="{{ route('resend-verification-email') }}">
              @csrf
            <div>
              <div>                
                <h3>Please verify your email</h3>
                <h4><u>{{ $email }}</u> </h4><br>
                <p>{{ $message }}</p>
              </div> 
              <div class="button input-box">
                <input type="submit" value="Didn't see it? Resend"  class="button button-lg button--primary w-100">
              </div>              
            </div>
        </form>
                </div>
            </div>
            <div class="col-xl-7 order-1 order-xl-0">
                <div class="signup-area-image">
                    <img src="{{asset('frontend/dist/images/sign/img-3.png')}}" alt="Illustration Image"
                        class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SignIn Area Ends Here -->
@endsection