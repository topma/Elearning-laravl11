@extends('frontend.layouts.app')
@section('title', 'About')
@section('header-attr') class="nav-shadow" @endsection

@section('content')
<!-- Breadcrumb Starts Here -->
<div class="py-0">
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('home')}}" class="fs-6 text-secondary">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="{{route('studentLogin')}}" class="fs-6 text-secondary">Signin</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb Ends Here -->

<!-- About Feature Starts Here -->
<section class="section aboutFeature pb-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-feature">
                    <h5 class="font-title--sm">Signin as a Student</h5>                    
                    <!-- Student Login form -->                                       
                    <form action="{{route('studentLogin.check','studentdashboard')}}" method="POST">
                        @csrf
                        <div class="form-element">
                            <label for="email">Email</label>
                            <input type="email" placeholder="Username" id="email" name="email" />
                            @if($errors->has('email'))
                            <small class="d-block text-danger">{{$errors->first('email')}}</small>
                            @endif
                        </div>
                        <div class="form-element">
                            <div class="d-flex justify-content-between">
                                <label for="password">Password</label>                                
                            </div>
                            <div class="form-alert-input">
                                <input type="password" placeholder="Type here..." id="password" name="password" />
                                <div class="form-alert-icon" onclick="showPassword('password',this);">
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
                        <div class="form-element d-flex align-items-center terms">
                            <input class="checkbox-primary me-1" type="checkbox" id="agree" />
                            <table width="100%">
                                <tr>
                                    <td><label for="agree" class="text-secondary mb-0 fs-6">Remember me</label></td>
                                    <td><div align="right"><a href="{{ route('user.password.request') }}" class="text-primary fs-6">Forgot Password ?</a></div></td>
                                </tr>
                            </table>
                            
                            
                        </div>
                        <div class="form-element">
                            <button type="submit" class="button button-lg button--primary w-100">Sign in</button>
                        </div>
                        <p class="mt-2 mb-lg-4 mb-3">Don't have account? <a href="{{route('studentRegister')}}"
                            class="text-black-50">Sign
                            up</a></p>
                    </form>                    
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="about-feature">
                    <h5 class="font-title--sm">Signin as an Instructor</h5>                                       
                    <!-- Student Login form -->                                       
                    <form action="{{route('instructorLogin.check','instructordashboard')}}" method="POST">
                        @csrf
                        <div class="form-element">
                            <label for="email">Email</label>
                            <input type="email" placeholder="Username" id="email" name="email" />
                            @if($errors->has('email'))
                            <small class="d-block text-danger">{{$errors->first('email')}}</small>
                            @endif
                        </div>
                        <div class="form-element">
                            <div class="d-flex justify-content-between">
                                <label for="password">Password</label>                                
                            </div>
                            <div class="form-alert-input">
                                <input type="password" placeholder="Type here..." id="password" name="password" />
                                <div class="form-alert-icon" onclick="showPassword('password',this);">
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
                        <div class="form-element d-flex align-items-center terms">
                            <input class="checkbox-primary me-1" type="checkbox" id="agree" />
                            <table width="100%">
                                <tr>
                                    <td><label for="agree" class="text-secondary mb-0 fs-6">Remember me</label></td>
                                    <td><div align="right"><a href="{{ route('password.request') }}" class="text-primary fs-6">Forgot Password ?</a></div></td>
                                </tr>
                            </table>
                            
                            
                        </div>
                        <div class="form-element">
                            <button type="submit" class="button button-lg button--primary w-100">Sign in</button>
                        </div>
                        <p class="mt-2 mb-lg-4 mb-3">Don't have account? <a href="{{route('studentRegister')}}"
                            class="text-black-50">Sign
                            up</a></p>
                    </form>              
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Feature Ends Here -->
 <section>
    <br>
    <br>
 </section>
@endsection

@push('scripts')
@endpush