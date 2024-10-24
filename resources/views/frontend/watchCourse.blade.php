<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ config('app.name') }} | @yield('title', 'Watch Course')</title>
    <link rel="stylesheet" href="{{asset('frontend/src/scss/vendors/plugin/css/video-js.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/src/scss/vendors/plugin/css/star-rating-svg.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/dist/main.css')}}" />
    <link rel="icon" type="image/png" href="{{asset('frontend/dist/images/favicon/favicon.png')}}" />
    <link rel="stylesheet" href="{{asset('frontend/fontawesome-free-5.15.4-web/css/all.min.css')}}">
    <link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .highlight {
        background-color: #e0f7fa; /* Light blue background for highlighting */
        border-left: 4px solid #007bff; /* Blue left border for additional emphasis */
    }
    
    .text-frame, .document-frame {
        max-height: 600px; /* Set your desired height */
        overflow-y: auto;  /* Enable vertical scrolling */
        border: 2px solid #ccc; /* Optional: Border for visual separation */
        padding: 10px; /* Optional: Padding for better spacing */
        background-color: #f9f9f9; /* Optional: Background color */
    }
    
</style>
<style>
    /* General video area styles */
.video-area {
    height: 700px; /* Set desired height for video container */
}

.text-area {
    height: 500px; /* Set desired height for video container */
}

/* Container to handle the video layout */
.video-container {
    position: relative;
    width: 100%; /* Full width */
    height: 100%; /* Full height to fill parent */
}

.text-container {
    position: relative;
    width: 100%; /* Full width */
    height: 100%; /* Full height to fill parent */
}

/* Ensure the video fills the container properly */
.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    object-fit: contain; /* Keep aspect ratio without cropping */
}

/* Customize video.js skin */
.video-js {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Ensure fullscreen and volume controls are visible */
.video-js .vjs-volume-panel,
.video-js .vjs-fullscreen-control {
    display: inline-block;
}

/* Optionally hide default browser controls panel (if needed) */
video::-webkit-media-controls-panel {
    display: none !important; /* Hide the default control panel */
}

/* Prevent selection and copying */
body {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

</style>

<style>
.videolist-area-bar {
    background-color: #e0e0e0; /* Light grey background */
    border-radius: 5px;
    height: 4px;
    width: 100%; /* Full width */
    margin: 10px 0;
    overflow: hidden; /* Hide overflow for rounded corners */
}

.videolist-area-bar--progress {
    background-color: green; /* Progress bar color */
    height: 100%;
    display: block;
    transition: width 0.3s ease; /* Smooth transition */
}
</style>
<style>
    .star-rating {
    font-size: 2rem;
    color: #ccc; /* Default color for stars */
}

.star {
    cursor: pointer;
    transition: color 0.2s;
}

.star:hover,
.star.selected {
    color: gold; /* Change color when hovered or selected */
}
</style>
</head>

<body style="background-color: #ebebf2;">

    <!-- Title Starts Here -->
    <header class="bg-transparent">
        <div class="container-fluid">
            <div class="coursedescription-header">
                <div class="coursedescription-header-start">
                    <a class="logo-image" href="{{route('home')}}">
                        <img src="{{asset('frontend/dist/images/logo/new_logo.png')}}" alt="Logo" />
                    </a>
                    <div class="topic-info">
                        <div class="topic-info-arrow">
                            <a href="{{ route('courseSegment', encryptor('encrypt', $courseId)) }}">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <div class="topic-info-text">
                            <h6 class="font-title--xs"><a href="#">{{$segment->title_en}}
                            </a></h6>
                            <div class="lesson-hours">
                                <div class="book-lesson">
                                    <i class="fas fa-book-open text-primary"></i>
                                    <span>{{$lessons->count()}} Lesson</span>
                                </div>
                                <!-- <div class="totoal-hours">
                                    <i class="far fa-clock text-danger"></i>
                                    <span>{{$course->duration?$course->duration:0}} Hours</span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="coursedescription-header-end">                
                    <!-- <a href="#" class="rating-link" data-bs-toggle="modal" data-bs-target="#ratingModal">Leave a Rating</a> -->
                    <a href="#" class="button button--dark" data-bs-toggle="modal" data-bs-target="#ratingModal">Leave a
                        Rating</a>
                    <a href="{{route('studentdashboard')}}" class="button button--primary">My Dashboard</a>

                    <!-- <a href="#" class="btn btn-primary regular-fill-btn">Next Lession</a> -->
                    <!-- <button class="button button--primary">Next Lesson</button> -->
                </div>
            </div>
        </div>
    </header>
    <!-- Ttile Ends Here -->

    <!-- Course Description Starts Here -->
    <div class="container-fluid">
        <div class="row course-description">

            {{-- Video Area --}}
            <div class="col-lg-8">
                <div class="course-description-start">
                <div id="lesson-container" style="position: relative;"> 
                    @if($currentMaterial->type == 'video')
                        <div class="video-area">                            
                            <div class="video-container">
                                @if(!empty($currentMaterial->content))
                                    <video controls id="myvideo" 
                                        class="video-js w-100" 
                                        poster="{{ asset('uploads/courses/contents/' . $currentMaterial->content) }}"
                                        data-setup='{"controls": true, "preload": "auto", "autoplay":true}'>
                                        <source src="{{ asset('uploads/courses/contents/' . $currentMaterial->content) }}" class="video-js w-100" autostart="true"/>
                                    </video>                                        
                                @else
                                    <p>No valid content available for this lesson.</p>
                                @endif
                            </div>
                        </div>
                    @elseif($currentMaterial->type == 'text')
                        <div class="lesson-text">
                            {!! $currentMaterial->content_data !!}
                        </div>
                    @elseif($currentMaterial->type == 'document')
                        <div class="document-content">
                            {!! $currentMaterial->content_data !!}
                        </div>
                    @else
                        <p>No valid content available for this lesson.</p>
                    @endif                    

                </div>
                <!-- Quiz Section (Initially hidden) -->
                    <div id="quiz-container" style="display: none; border: 2px solid #ccc; padding: 20px; border-radius: 10px; margin: 20px auto; max-width: 100%; background-color: #fff;">
                        <div class="text-area">
                            <div class="text-container">
                                <!-- Question content with number -->
                                <div id="question-content" style="font-size: 28px; font-weight: bold; margin-bottom: 20px;"></div>
                                <br>
                                <!-- Options with A, B, C, D labels -->
                                <div id="options" style="font-size: 24px;">
                                    <label><input type="radio" name="answer" value="a"> A. <span id="option-a"></span></label><br>
                                    <label><input type="radio" name="answer" value="b"> B. <span id="option-b"></span></label><br>
                                    <label><input type="radio" name="answer" value="c"> C. <span id="option-c"></span></label><br>
                                    <label><input type="radio" name="answer" value="d"> D. <span id="option-d"></span></label>
                                </div>
                                <br>
                                <!-- Quiz navigation buttons -->
                                <div class="quiz-navigation" style="margin-top: 20px; text-align: center;">
                                    <button id="prev-question" disabled style="font-size: 16px; padding: 10px 15px; background-color: #f5a623; border: none; color: white; cursor: pointer;">Previous</button>
                                    <button id="next-question" style="font-size: 16px; padding: 10px 15px; background-color: #4a90e2; border: none; color: white; cursor: pointer;">Next</button>
                                    <button id="finish-quiz" style="display:none; font-size: 16px; padding: 10px 15px; background-color: #7ed321; border: none; color: white; cursor: pointer;">Finish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-container">
                    <div class="course-description-start-content">                       
                            <h5 class="font-title--sm material-title">{{$currentLesson->title}}</h5>
                            <nav class="course-description-start-content-tab">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-ldescrip-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-ldescrip" type="button" role="tab" aria-controls="nav-ldescrip"
                                        aria-selected="true">
                                        Lesson Description
                                    </button>
                                    <button class="nav-link" id="nav-lnotes-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-lnotes" type="button" role="tab" aria-controls="nav-lnotes"
                                        aria-selected="false">Lesson Notes</button>
                                    <button class="nav-link" id="nav-lcomments-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-lcomments" type="button" role="tab"
                                        aria-controls="nav-lcomments" aria-selected="false">Comments</button>
                                    <button class="nav-link" id="nav-loverview-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-loverview" type="button" role="tab"
                                        aria-controls="nav-loverview" aria-selected="false">Course Overview</button>
                                    <button class="nav-link" id="nav-linstruc-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-linstruc" type="button" role="tab" aria-controls="nav-linstruc"
                                        aria-selected="false">Instructor</button>
                                </div>
                            </nav>                        
                        
                        <div class="tab-content course-description-start-content-tabitem" id="nav-tabContent">
                            <!-- Lesson Description Starts Here -->
                            <div class="tab-pane fade show active" id="nav-ldescrip" role="tabpanel"
                                aria-labelledby="nav-ldescrip-tab">
                                <div class="lesson-description">
                                    <p>
                                    {{$currentLesson->description}}
                                    </p>
                                </div>
                                <!-- Lesson Description Ends Here -->
                            </div>
                            <!-- Course Notes Starts Here -->
                            <div class="tab-pane fade" id="nav-lnotes" role="tabpanel" aria-labelledby="nav-lnotes-tab">
                                <div class="course-notes-area">
                                    <div class="course-notes">
                                        <div class="course-notes-item">
                                            <p>
                                            {{$currentLesson->notes}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Course Notes Ends Here -->
                            </div>
                            <!-- Lesson Comments Starts Here -->
                        <div class="tab-pane fade" id="nav-lcomments" role="tabpanel"
                                aria-labelledby="nav-lcomments-tab">
                            <div class="lesson-comments">
                                <div class="feedback-comment pt-0 ps-0 pe-0">
                                    <h6 class="font-title--card">Add a Comment about this course.</h6>
                                    <form id="comment-form">
                                        @csrf
                                        <label for="comment">Comment</label>
                                        <textarea class="form-control" id="comment" name="comment" placeholder="Add a Comment" required></textarea>
                                        <input type="hidden" name="student_id" id="student_id" value="{{ $studentId }}">
                                        <input type="hidden" name="course_id" id="course_id" value="{{ $courseId }}">
                                        <button type="submit" class="button button-md button--primary float-end">Post Comment</button>
                                    </form>
                                </div>

                                <!-- Display Comments Section -->
                                <div class="students-feedback pt-0 ps-0 pe-0 pb-0 mb-0">
                                    <div class="students-feedback-heading">
                                        <h5 class="font-title--card">Comments <span id="comment-count">({{ $course->reviews->count() }})</span></h5>
                                    </div>
                                    <div id="comments-container">
                                        <!-- Comments will be loaded here dynamically -->
                                        @foreach($course->reviews as $review)
                                            <div class="students-feedback-item">
                                                <div class="feedback-rating">
                                                    <div class="feedback-rating-start">
                                                        <div class="image">
                                                            <img src="{{ $review->student->image ? asset('uploads/students/' . $review->student->image) : asset('frontend/dist/images/ellipse/2.png') }}" alt="Image"  />
                                                        </div>
                                                        <div class="text">
                                                            <h6><a href="#">{{ $review->student->name_en }}</a></h6>
                                                            <p>{{ $review->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>{{ $review->comment }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Lesson Comments Ends Here -->
                            </div>
                        </div>
                            <!-- Course Overview Starts Here -->
                            <div class="tab-pane fade" id="nav-loverview" role="tabpanel"
                                aria-labelledby="nav-loverview-tab">
                                <div class="row course-overview-main">
                                    <div class="course-overview-main-item">
                                        <h6 class="font-title--card">Description</h6>
                                        <p class="mb-3 font-para--lg">
                                           {{$course->description_en}}
                                        </p>
                                    </div>
                                    <div class="course-overview-main-item">
                                        <h6 class="font-title--card">Requirments</h6>
                                        <p class="mb-2 font-para--lg">
                                           {{$course->prerequisites_en}}
                                        </p>
                                    </div>
                                </div>
                                <!-- Course Overview Ends Here -->
                            </div>
                            <!-- course details instructor  -->
                            <div class="tab-pane fade" id="nav-linstruc" role="tabpanel"
                                aria-labelledby="nav-linstruc-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="course-instructor mw-100">
                                            <div class="course-instructor-info">
                                                <div class="instructor-image">
                                                    <img src="{{asset('uploads/users/'.$course?->instructor?->image)}}"
                                                        alt="Instructor" width="160" height="120" />
                                                </div>
                                                <div class="instructor-text">
                                                    <h6 class="font-title--xs">
                                                        <a href="{{route('instructorProfile', encryptor('encrypt', $course->instructor->id))}}">
                                                            {{$course?->instructor?->name_en}}</a></h6>
                                                    <p>{{$course?->instructor?->designation}}</p>
                                                    <div class="d-flex align-items-center instructor-text-bottom">
                                                        <div class="d-flex align-items-center ratings-icon">
                                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M9.94438 2.34287L11.7457 5.96656C11.8359 6.14934 12.0102 6.2769 12.2124 6.30645L16.2452 6.88901C16.4085 6.91079 16.5555 6.99635 16.6559 7.12701C16.8441 7.37201 16.8153 7.71891 16.5898 7.92969L13.6668 10.7561C13.5183 10.8961 13.4522 11.1015 13.4911 11.3014L14.1911 15.2898C14.2401 15.6204 14.0145 15.93 13.684 15.9836C13.5471 16.0046 13.4071 15.9829 13.2826 15.9214L9.69082 14.0384C9.51037 13.9404 9.29415 13.9404 9.1137 14.0384L5.49546 15.9315C5.1929 16.0855 4.82267 15.9712 4.65778 15.6748C4.59478 15.5551 4.57301 15.419 4.59478 15.286L5.29479 11.2975C5.32979 11.0984 5.26368 10.8938 5.11901 10.753L2.18055 7.92735C1.94099 7.68935 1.93943 7.30201 2.17821 7.06246C2.17899 7.06168 2.17977 7.06012 2.18055 7.05935C2.27932 6.9699 2.40066 6.91001 2.5321 6.88668L6.56569 6.30412C6.76713 6.27223 6.94058 6.14623 7.03236 5.96345L8.83215 2.34287C8.90448 2.19587 9.03281 2.08309 9.18837 2.03176C9.3447 1.97965 9.51582 1.99209 9.66282 2.06598C9.78337 2.12587 9.88215 2.22309 9.94438 2.34287Z"
                                                                    stroke="#FF7A1A" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                            </svg>
                                                            <p>4.9 Star Rating</p>
                                                        </div>
                                                        <div class="d-flex align-items-center ratings-icon">
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M1.5 2.25H6C6.79565 2.25 7.55871 2.56607 8.12132 3.12868C8.68393 3.69129 9 4.45435 9 5.25V15.75C9 15.1533 8.76295 14.581 8.34099 14.159C7.91903 13.7371 7.34674 13.5 6.75 13.5H1.5V2.25Z"
                                                                    stroke="#00AF91" stroke-width="1.8"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                                <path
                                                                    d="M16.5 2.25H12C11.2044 2.25 10.4413 2.56607 9.87868 3.12868C9.31607 3.69129 9 4.45435 9 5.25V15.75C9 15.1533 9.23705 14.581 9.65901 14.159C10.081 13.7371 10.6533 13.5 11.25 13.5H16.5V2.25Z"
                                                                    stroke="#00AF91" stroke-width="1.8"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                            </svg>

                                                            @if($courseNo->count() > 1)
                                                            <p class="font-para--md">{{$courseNo->count()}} Courses</p>
                                                            @elseif($courseNo->count() == 1)
                                                            <p class="font-para--md">{{$courseNo->count()}} Course</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <p class="lead-p">{{$course?->instructor?->title}} -->
                                            </p>
                                            <p class="course-instructor-description">
                                                {!! $course?->instructor?->bio !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    </div>                 
                </div>
            </div>

            {{-- Index Course Contents --}}
            <div class="col-lg-4">
                <div class="videolist-area">
                    <div class="videolist-area-heading">
                        <h6>Course Contents</h6>
                    </div>
                   
                    <div class="videolist-area-bar">
                        <span class="videolist-area-bar--progress" 
                            style="width: {{ $segmentProgress[$segment->id] ?? 0 }}%; background-color: green; display: block;">
                        </span>
                    </div>
                    <!-- <div>
                        <p>Progress: {{ $segmentProgress[$segment->id] ?? 0 }}%</p>
                    </div> -->
                    <div class="videolist-area-bar__wrapper">
                        @foreach($lessons as $lesson)
                            <div class="videolist-area-wizard" 
                                data-lesson-description="{{$lesson->description}}"
                                data-lesson-notes="{{$lesson->notes}}">
                                <div class="wizard-heading">
                                    <h6 class="">{{$loop->iteration}}. {{$lesson->title}}</h6>
                                </div>
                                @foreach ($lesson->material as $material)
                                    <div class="main-wizard lesson-wizard"
                                        data-lesson-id="{{ $lesson->id }}"
                                        data-material-title="{{$loop->parent->iteration}}.{{$loop->iteration}} {{$material->title}}"
                                        data-material-type="{{$material->type}}"
                                        data-material-content="{{$material->content}}"
                                        data-material-content-data="{{ $material->content_data }}"
                                        data-material-description="{{$lesson->description}}"
                                        data-material-notes="{{$lesson->notes}}"
                                        data-material-id="{{ $material->id }}"
                                        data-course-id="{{$course->id}}"
                                        data-segment-id="{{$segment->id}}"
                                        data-segment-no="{{$segment->segment_no}}">
                                        
                                        <div class="main-wizard__wrapper">
                                            <a class="main-wizard-start lesson-start">
                                                @if ($material->type == 'video')
                                                    <div class="main-wizard-icon">
                                                        <i class="far fa-play-circle fa-lg"></i>
                                                    </div>
                                                @else
                                                    <div class="main-wizard-icon">
                                                        <i class="far fa-file fa-lg text-success"></i>
                                                    </div>
                                                @endif
                                                <div class="main-wizard-title">
                                                    <p>{{$loop->parent->iteration}}.{{$loop->iteration}} {{$material->title}}</p>
                                                </div>
                                            </a>
                                            <div class="main-wizard-end d-flex align-items-center">
                                                @if ($material->type == 'video')
                                                    <strong><span style="color:green;">{{$material->file_duration}}</span></strong>
                                                @else
                                                    <span></span>
                                                @endif
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        style="border-radius: 0px; margin-left: 5px;"
                                                        @if(in_array($material->id, $progressRecords)) checked @endif />
                                                </div>
                                            </div>                                            
                                        </div>                                    
                                    </div>
                                @endforeach
                            </div>                            
                        @endforeach  

                        @if($questions->count() > 0)
                        <div class="videolist-area-wizard"> 
                            <div class="wizard-heading">
                                <h6 class="">Quiz</h6>
                            </div> 
                            <div class="main-wizard quiz-wizard" data-quiz-id="{{ $quiz->id }}">
                                <div class="main-wizard__wrapper"> 
                                    <button class="button button--primary start-quiz-btn" 
                                    data-quiz-id="{{$quiz->id}}"
                                    data-quiz-pass-mark="{{$quiz->pass_mark}}"
                                    data-student-id="{{$studentId}}"
                                    data-course-id="{{$course->id}}"
                                    data-segment-id="{{$segment->id}}"
                                    data-segment-no="{{$segment->segment_no}}">
                                    Start Quiz</button>
                                </div>  
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
    <!-- Course Description Ends Here -->

    <!-- Rating Modal -->
    
        <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" 
        aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Leave A Rating</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <!-- Star Rating -->
                        <div class="star-rating">
                            <i class="star fa fa-star" data-value="1"></i>
                            <i class="star fa fa-star" data-value="2"></i>
                            <i class="star fa fa-star" data-value="3"></i>
                            <i class="star fa fa-star" data-value="4"></i>
                            <i class="star fa fa-star" data-value="5"></i>
                        </div>
                        <p class="mt-2">Rating: <span id="selected-rating">0</span> / 5</p>
                    </div>
                    <!-- Rating Form -->
                    <form id="rating-form">
                        <input type="hidden" id="rating" name="rating" value="0"> <!-- Hidden field for rating -->
                        <input type="hidden" id="course_id" name="course_id" value="{{ $courseId }}">
                        <input type="hidden" id="student_id" name="student_id" value="{{ $studentId }}">
                        <!-- <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="3" placeholder="How do you feel about this course?"></textarea>
                        </div> -->
                        <button type="submit" class="btn btn-primary w-100">Submit Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script src="{{asset('frontend/src/js/jquery.min.js')}}"></script>
    <script src="{{asset('frontend/src/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/src/scss/vendors/plugin/js/video.min.js')}}"></script>
    <script src="{{asset('frontend/src/scss/vendors/plugin/js/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('frontend/src/scss/vendors/plugin/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('frontend/src/scss/vendors/plugin/js/slick.min.js')}}"></script>
    <script src="{{asset('frontend/src/scss/vendors/plugin/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('frontend/src/scss/vendors/plugin/js/jquery.star-rating-svg.js')}}"></script>
    <script src="{{asset('frontend/src/js/app.js')}}"></script>
    <script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Lesson -->
    <script>
    function show_content(material) {
        const contentType = material.type; // Get the type of content
        const contentLink = "{{ asset('uploads/courses/contents') }}/" + material.content; // Construct the link

        // Hide the lesson container initially
        $('#lesson-container').empty(); // Clear existing content

        // Check if the content is video or text
        if (contentType === 'video') {
            // If it's a video, set the video source and create the video element
            const videoHTML = `            
                <div class="video-area">
                    <div class="video-container">
                        <video controls id="myvideo" 
                                    class="video-js w-100" 
                                    poster="${contentLink}"
                                    data-setup='{"controls": true, "preload": "auto", "autoplay":true}'>
                                    <source src="${contentLink}" class="w-100" autostart="true"/>
                        </video>  
                    </div>
                </div>
            `;
            $('#lesson-container').append(videoHTML);
            $('#myvideo').get(0).play(); // Play the video
            $('#quiz-container').hide(); 
            $('#tab-container').show();// show lesson tab container
            scrollToTop(); // Scroll to top of lesson
        } else if (contentType === 'text') {
            // If it's text, display it within a styled frame
            const textHTML = `
                <div class="text-area">
                    <div class="text-frame">
                        <p>${material.content_data ? material.content_data : 'No content available.'}</p>
                    </div>
                </div>
            `;
            $('#lesson-container').append(textHTML);
            $('#quiz-container').hide(); // Hide quiz container
            $('#tab-container').show();// show lesson tab container
            scrollToTop(); // Scroll to top of lesson
        } else if (contentType === 'document') {
            // If it's a document, display it
            const documentHTML = `
                <div class="document-content">
                    <div class="document-frame">
                        ${material.content_data ? material.content_data : 'No content available.'}
                    </div>
                </div>
            `;
            $('#lesson-container').append(documentHTML);
            $('#quiz-container').hide(); // Hide quiz container
            $('#tab-container').show();// show lesson tab container
            scrollToTop(); // Scroll to top of lesson
        } else {
            // Handle other types of content if necessary
            console.log('No valid content available for this lesson.');
            // alert('No valid content available for this lesson.');
        }

        // Scroll to the top of the lesson container
        function scrollToTop() {
            $('html, body').animate({
                scrollTop: $('#lesson-container').offset().top
            }, 'fast'); // 'fast' for a quick scroll
        }
    }

    $(document).ready(function() {
        // Set the initial checked state for checkboxes based on progress records
        var progressRecords = @json($progressRecords); // Pass this from the server-side
        $('.main-wizard').each(function() {
            var materialId = $(this).data('material-id');
            if (progressRecords.includes(materialId)) {
                $(this).find('.form-check-input').prop('checked', true); // Check the checkbox if progress exists
            }
        });

        $('.main-wizard').on('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get material data attributes
            var material = {
                title: $(this).data('material-title'),
                type: $(this).data('material-type'),
                content: $(this).data('material-content'),
                content_data: $(this).data('material-content-data') || '', // Ensure it's set
                description: $(this).data('material-description'), // Capture lesson description
                notes: $(this).data('material-notes'), // Capture lesson notes
                id: $(this).data('material-id'), // Capture material ID
                course_id: $(this).data('course-id'), // Capture course ID
                lesson_id: $(this).data('lesson-id') ,// Capture lesson ID
                segment_id: $(this).data('segment-id') ,
                segment_no: $(this).data('segment-no') 
            };

            // Check the checkbox for the clicked lesson
            $(this).find('.form-check-input').prop('checked', true); // Check the current checkbox

            // Highlight the current lesson
            $(this).addClass('highlight'); // Add highlight class to the clicked lesson

            // Update material title
            $('.material-title').html(material.title);

            // Update lesson description and notes
            $('#nav-ldescrip .lesson-description p').html(material.description); // Update description
            $('#nav-lnotes .course-notes-area .course-notes-item p').html(material.notes); // Update notes
            
            // Show content based on the type
            show_content(material);

            // Send AJAX request to update progress
            $.ajax({
                url: "{{ route('update.progress') }}", 
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",                   
                    courseid: material.course_id,
                    lessonid: material.lesson_id, // Use the captured lesson ID
                    materialid: material.id,
                    segmentid: material.segment_id,
                    segmentno: material.segment_no
                },
                success: function(response) {
                    console.log('Progress updated successfully');
                },
                error: function(error) {
                    // console.log('Error updating progress:', error);
                }
            });
        });
    });
</script>

<!-- Quiz -->
<script>
let questions = [];
let currentQuestionIndex = 0;
let selectedAnswers = {};

// Function to save question and selected answer to the database
function saveQuestionResponse(questionId, studentId, answer = '') {
    $.ajax({
        url: `/students/quiz/save-answer`, // Adjust the URL based on your routing structure
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            student_id: studentId,
            question_id: questionId,
            answer: answer // Default to empty string if no answer selected
        },
        success: function(response) {
            console.log('Answer saved successfully.');
        },
        error: function() {
            console.log('Failed to save the answer.');
        }
    });
}

// Function to load a question based on the current index
function loadQuestion(index) {
    const question = questions[index];
    if (!question) return;

    // Display the total number of questions
    $('#total-questions').text(`Total Questions: ${questions.length}`);
    
    // Display the current question in the desired format with a line break
    $('#question-content').html(`Question ${index + 1} of ${questions.length}: <br>${question.content}`);
    $('#option-a').text(question.option_a);
    $('#option-b').text(question.option_b);
    $('#option-c').text(question.option_c);
    $('#option-d').text(question.option_d);

    // Reset previously selected answer
    $('input[name="answer"]').prop('checked', false);
    if (selectedAnswers[question.id]) {
        $(`input[name="answer"][value="${selectedAnswers[question.id]}"]`).prop('checked', true);
    }

    // Update navigation button states
    $('#prev-question').prop('disabled', index === 0);
    $('#next-question').toggle(index < questions.length - 1);
    $('#finish-quiz').toggle(index === questions.length - 1);

    // Auto-save the question when loaded (even if no answer is selected yet)
    const studentId = $('.start-quiz-btn').data('student-id');
    saveQuestionResponse(question.id, studentId, selectedAnswers[question.id] || '');
}

// Save the selected answer for the current question
function saveAnswer() {
    const selectedAnswer = $('input[name="answer"]:checked').val();
    if (selectedAnswer) {
        const currentQuestionId = questions[currentQuestionIndex].id;
        selectedAnswers[currentQuestionId] = selectedAnswer;

        // Auto-save the answer immediately when selected
        const studentId = $('.start-quiz-btn').data('student-id');
        saveQuestionResponse(currentQuestionId, studentId, selectedAnswer);
    }
}

// Calculate and return the quiz score as a percentage
function calculateScore() {
    let correctAnswers = 0;
    questions.forEach((question) => {
        if (selectedAnswers[question.id] === question.correct_answer) {
            correctAnswers++;
        }
    });
    return (correctAnswers / questions.length) * 100;
}

// Display user results (correct and incorrect answers)
function displayResults() {
    let resultHTML = '<div style="border: 2px solid #ccc; padding: 20px; border-radius: 10px; background-color: #fff;">';
    resultHTML += '<h2>Quiz Results</h2><ul>';

    questions.forEach((question, index) => {
        const userAnswer = selectedAnswers[question.id] || 'No answer';
        const isCorrect = userAnswer === question.correct_answer;
        const resultClass = isCorrect ? 'correct-answer' : 'wrong-answer';
        const correctAnswerText = isCorrect ? 'Correct!' : `Wrong! Correct answer: ${question.correct_answer.toUpperCase()}`;

        resultHTML += `
            <li style="margin-bottom: 10px;">
                <strong>Question ${index + 1}:</strong> ${question.content}<br>
                <span class="${resultClass}">Your answer: ${userAnswer.toUpperCase()}</span><br>
                <span>${correctAnswerText}</span>
            </li>`;
    });

    resultHTML += '</ul></div>';
    return resultHTML;
}

// Fetch quiz questions when the quiz is clicked
function fetchQuizQuestions(quizId) {
    $.ajax({
        url: `/students/quiz/${quizId}/questions`,
        method: 'GET',
        success: function(data) {
            questions = data;
            if (questions.length > 0) {
                $('#quiz-container').show();
                $('#tab-container').hide(); // hide the lesson tab container
                loadQuestion(0); // Load the first question
                
                // Scroll to top of quiz container
                $('html, body').animate({
                    scrollTop: $('#quiz-container').offset().top
                }, 'fast');
            } else {
                $('#quiz-container').html('<p>No questions available for this quiz.</p>');
            }
        },
        error: function() {
            $('#quiz-container').html('<p>Failed to load questions. Please try again later.</p>');
        }
    });
}

$(document).ready(function() {
    // Event listener to start quiz when the button is clicked
    $('.start-quiz-btn').click(function() {
        const quizId = $(this).data('quiz-id');
        
        // Fetch quiz questions and display them
        fetchQuizQuestions(quizId);
        
        // Scroll to top when quiz starts
        $('html, body').animate({
            scrollTop: $('#quiz-container').offset().top
        }, 'fast');
    });

    // Handle question navigation (Next and Previous buttons)
    $('#next-question').click(() => {
        saveAnswer(); // Save answer before moving to the next question
        currentQuestionIndex++;
        loadQuestion(currentQuestionIndex); // Load the next question
    });

    $('#prev-question').click(() => {
        saveAnswer(); // Save answer before moving to the previous question
        currentQuestionIndex--;
        loadQuestion(currentQuestionIndex); // Load the previous question
    });

    // Finish the quiz and display results
    $('#finish-quiz').click(() => {
        saveAnswer(); // Save the last answer before finishing
        const scorePercentage = calculateScore();
        const resultHTML = displayResults(); // Display correct and incorrect answers

        // Display the results with score and styled container
        $('#quiz-container').html(`
            <div style="text-align: center; padding: 30px; border-radius: 10px; background-color: #f5f5f5; max-width: 600px; margin: 0 auto;">
                <h3>Quiz Finished!</h3>
                <p>Your score: <strong>${scorePercentage}%</strong></p>
                ${resultHTML}                
            </div>
        `);
    });

    // Auto-save answer when an option is selected
    $('input[name="answer"]').change(function() {
        saveAnswer(); // Save answer immediately on selection
    });
});
</script>


<!-- User Comments -->
<script>
    $(document).ready(function () {
        // Submit comment via AJAX
        $('#comment-form').submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            let formData = {
                comment: $('#comment').val(),
                student_id: $('#student_id').val(),
                course_id: $('#course_id').val(),
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                url: "{{ route('review.save') }}",  // Your route to save the comment
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        // Clear the comment textarea
                        $('#comment').val('');

                        // Reload the comments section
                        loadComments();
                    } else {
                        alert('Error: ' + response.message); // Handle error message
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error submitting the comment: ', error);
                    alert('Error submitting the comment. Please try again.');
                }
            });
        });

        // Function to load comments dynamically
        function loadComments() {
            let courseId = $('#course_id').val(); // Get the course ID from the form

            $.ajax({
                url: "/students/course-review/" + courseId,  // Adjust the route to fetch reviews
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        // Populate the comments container with the fetched comments
                        $('#comments-container').html(response.html);

                        // Update the comment count
                        $('#comment-count').text(`(${response.count})`);
                    } else {
                        alert('Error loading comments.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading comments: ', error);
                    alert('Error loading comments. Please try again.');
                }
            });
        }

        // Initially load comments when the page is ready
        loadComments();
    });
</script>
<!-- User Rating  -->
<script>
    $(document).ready(function () {
        // Handle star click
        $('.star').click(function () {
            let ratingValue = $(this).data('value');
            
            // Update the hidden input field
            $('#rating').val(ratingValue);

            // Update the displayed rating
            $('#selected-rating').text(ratingValue);

            // Highlight the selected stars and reset the others
            $('.star').each(function () {
                if ($(this).data('value') <= ratingValue) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });
        });

        // Submit rating form via AJAX
        $('#rating-form').submit(function (event) {
            event.preventDefault();

            let formData = {
                rating: $('#rating').val(),
                course_id: $('#course_id').val(),
                student_id: $('#student_id').val(),
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                url: "{{ route('course.rating.store') }}",  // Your route to store the rating
                method: 'POST',
                data: formData,
                success: function (response) {
                    // Close the modal
                    $('#ratingModal').modal('hide');
                    
                    // Show a success message
                    alert('Rating submitted successfully!');
                    
                    // Reload the page after a short delay (e.g., 1 second)
                    setTimeout(function() {
                        window.location.reload();  // Reload the page
                    }, 1000);  // 1 second delay
                },
                error: function (response) {
                    alert('Error submitting rating.');
                }
            });
        });
    });
</script>

<script>
    // Disable right-click and keyboard shortcuts
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && (e.key === 'c' || e.key === 's')) {
            e.preventDefault();
        }
    });
</script>



{{-- TOASTER --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <script>
        @if(Session::has('success'))  
        				toastr.success("{{ Session::get('success') }}");  
        		@endif  
        		@if(Session::has('info'))  
        				toastr.info("{{ Session::get('info') }}");  
        		@endif  
        		@if(Session::has('warning'))  
        				toastr.warning("{{ Session::get('warning') }}");  
        		@endif  
        		@if(Session::has('error'))  
        				toastr.error("{{ Session::get('error') }}");  
        		@endif  
    </script>


</body>

</html>