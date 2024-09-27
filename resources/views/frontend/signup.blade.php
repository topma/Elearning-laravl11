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
                    <a href="{{route('signup')}}" class="fs-6 text-secondary">Signup</a>
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
                <div class="about-feature dark-feature">
                    <h5 class="text-white font-title--sm">Signup as a Student</h5>
                    <p class="text-lowblack">
                    Join Kings Digital Literacy Hub as a student and unlock access to a wide range of courses and resources. Start learning today, 
                    enhance your digital skills, and take the next step in your career!
                    </p><br>
                    <a href="{{route('studentRegister')}}" class="button button-lg button--primary w-100">Get started</a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="about-feature">
                    <h5 class="font-title--sm">Signup as an Instructor</h5>
                    <p class="text-secondary">
                    Become an instructor at Kings Digital Literacy Hub and share your knowledge with eager learners. Create impactful courses, 
                    grow your teaching portfolio, and make a difference in the digital education world!
                    </p>
                    <br>
                    <a href="{{route('instructorRegister')}}" class="button button-lg button--dark w-100">Get started</a>
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