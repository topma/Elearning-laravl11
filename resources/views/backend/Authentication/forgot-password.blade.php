@extends('backend.layouts.appAuth')
@section('title', 'Log In')

@section('content')

<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <br>
                            <div align="center"><img src="{{asset('images/kdh_logo_big.png')}}" width="100" height="98" alt=""></div>
                            <div class="auth-form">
                                <h4 class="text-center mb-4"><strong>Reset Password</strong> </h4>
                                @if(auth()->guest()) 
                                <form action="{{ route('password.email') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label><strong>Email</strong></label>
                                        <input type="text" class="form-control" value="{{old('email')}}"
                                            name="email" id="email">
                                        @if($errors->has('email'))
                                        <small class="d-block text-danger">{{$errors->first('email')}}</small>
                                        @endif
                                    </div>                                   
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
                                    </div>
                                </form>
                                @else
    <p>You are already logged in. You cannot reset your password while logged in.</p>
@endif
                                <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="{{route('signup')}}">Sign
                                            up</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection