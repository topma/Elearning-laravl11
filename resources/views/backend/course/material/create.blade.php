@extends('backend.layouts.app')
@section('title', 'Add Course Material')

@push('styles')
<!-- Pick date -->
<link rel="stylesheet" href="{{asset('vendor/pickadate/themes/default.css')}}">
<link rel="stylesheet" href="{{asset('vendor/pickadate/themes/default.date.css')}}">
@endpush

@section('content')

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">

        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Add Course Material - {{$lesson->title}}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('course.index')}}">My Courses</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('lesson.show', encryptor('encrypt',$lesson->course_id))}}">Course Lesson</a></li>
                    <li class="breadcrumb-item active"><a href="">Add Course Material</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Basic Info</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('material.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Title</label>
                                        <input type="hidden" class="form-control" name="materialTitle"
                                            value="{{$lesson->title}}">
                                    </div>
                                    @if($errors->has('materialTitle'))
                                    <span class="text-danger"> {{ $errors->first('materialTitle') }}</span>
                                    @endif
                                </div>  -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Lesson</label>
                                        <select class="form-control" name="lessonId">                                            
                                            <option value="{{$lesson->id}}" {{old('lessonId')==$lesson->id?'selected':''}}>
                                                {{$lesson->title}}</option>                                           
                                            
                                        </select>
                                    </div>
                                    @if($errors->has('lessonId'))
                                    <span class="text-danger"> {{ $errors->first('lessonId') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Material Type</label>
                                        <select class="form-control" name="materialType">
                                            <option value="video" @if(old('materialType')=='video' ) selected @endif>
                                                Video
                                            </option>
                                            <option value="text" @if(old('materialType')=='text' ) selected
                                                @endif>Text
                                            </option>
                                            <!-- <option value="quiz" @if(old('materialType')=='quiz' ) selected @endif>Quiz
                                            </option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Content(Video)</label>
                                        <input type="file" class="form-control" name="content">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Content(Text)</label>
                                        <textarea class="form-control"
                                            name="contentData" id="myEditor">{{old('contentData')}}</textarea>
                                    </div>
                                    @if($errors->has('contentData'))
                                    <span class="text-danger"> {{ $errors->first('contentData') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                <input type="hidden" class="form-control" name="materialTitle" value="{{$lesson->title}}">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="submit" class="btn btn-light">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts') 
<!-- pickdate -->
<script src="{{asset('vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('vendor/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendor/pickadate/picker.date.js')}}"></script>

<!-- Pickdate -->
<script src="{{asset('js/plugins-init/pickadate-init.js')}}"></script>

@endpush

