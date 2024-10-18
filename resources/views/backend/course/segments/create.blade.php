@extends('backend.layouts.app')
@section('title', 'Add Course')

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
                    <h4>Add Segment - {{$course->title_en}}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('course.index')}}">Courses</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('segment.show', encryptor('encrypt', $course->id))}}">Segments</a></li>
                    <li class="breadcrumb-item active"><a href="#">Add Segment</a></li>
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
                        <form action="{{route('segment.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Segment No</label>
                                        <input type="number" class="form-control" name="segmentNo"
                                            value="{{old('segmentNo')}}">
                                    </div>
                                    @if($errors->has('segmentNo'))
                                    <span class="text-danger"> {{ $errors->first('segmentNo') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Segment Name</label>
                                        <input type="text" class="form-control" name="title_en"
                                            value="{{old('title_en')}}">
                                    </div>
                                    @if($errors->has('title_en'))
                                    <span class="text-danger"> {{ $errors->first('title_en') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description_en">{{old('description_en')}}</textarea>
                                    </div>
                                    @if($errors->has('description_en'))
                                    <span class="text-danger"> {{ $errors->first('description_en') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Number of Lesson</label>
                                        <input type="number" class="form-control" name="lesson"
                                            value="{{old('lesson')}}">
                                    </div>
                                    @if($errors->has('lesson'))
                                    <span class="text-danger"> {{ $errors->first('lesson') }}</span>
                                    @endif
                                </div>  
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Image</label>
                                    <div class="form-group fallback w-100">
                                        <input type="file" class="dropify" data-default-file="" name="image">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Thumbnail Image</label>
                                    <div class="form-group fallback w-100">
                                        <input type="file" class="dropify" data-default-file="" name="thumbnail_image">
                                    </div>
                                </div>                             
                               <!-- <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Status</label> 
                                        <select class="form-control" name="status">
                                            <option value="1" @if(old('status')==1) selected @endif>Active</option>
                                            <option value="0" @if(old('status')==0) selected @endif>Inactive</option>
                                        </select>
                                    </div>
                                </div> -->
                                
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input type="hidden" name="courseId" value="{{$course->id}}">
                                    <input type="hidden" name="categoryId" value="{{$course->course_category_id}}">
                                    <input type="hidden" name="instructorId" value="{{$instructor->id}}">
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