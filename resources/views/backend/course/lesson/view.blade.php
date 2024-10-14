@extends('backend.layouts.app')
@section('title', 'Course Lesson List')

@push('styles')
<!-- Datatable -->
<link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
@endpush

@section('content')

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">

        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Course Lesson List - {{$course->title_en}}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('course.index')}}">My Courses</a></li>
                    <li class="breadcrumb-item active"><a href="#">Course Lessons</a></li>                    
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"><a href="#list-view" data-toggle="tab"
                            class="nav-link btn-primary mr-1 show active">List View</a></li>
                    <!-- <li class="nav-item"><a href="javascript:void(0);" data-toggle="tab"
                            class="nav-link btn-primary">Grid
                            View</a></li> -->
                </ul>
            </div>
            <div class="col-lg-12">
                <div class="row tab-content">
                    <div id="list-view" class="tab-pane fade active show col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Course Lessons List </h4>
                                <a href="{{route('lesson.create',['course_id' => encryptor('encrypt', $course->id)])}}" class="btn btn-primary">+ Add new lesson</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>{{__('#')}}</th>
                                                <th>{{__('Title')}}</th>
                                                <th>{{__('Notes')}}</th>
                                                <th>{{__('Materials Uploaded')}}</th>
                                                <th></th>
                                                <!-- <th>{{__('Course')}}</th> -->
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($lesson as $key => $l)
                                            <tr>
                                                <td>{{ $l->serial_no }}</td>
                                                <td>{{ $l->title }}</td>
                                                <td>{{ $l->notes }}</td>
                                                <td>{{ $l->material_count }}</td>
                                                <td>
                                                    @if($l->material_count > 0)
                                                        <a href="{{ route('material.show', encryptor('encrypt', $l->id)) }}" 
                                                        class="btn btn-info" title="View Materials">View Materials</a>
                                                    @else
                                                        <a href="{{ route('material.createNew', ['id' => encryptor('encrypt', $l->id)]) }}" 
                                                        class="btn btn-dark" title="Add Materials">+ Add Materials</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('lesson.edit', encryptor('encrypt', $l->id)) }}" 
                                                    class="btn btn-sm btn-primary" title="Edit"><i class="la la-pencil"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger" 
                                                    title="Delete" onclick="$('#form{{ $l->id }}').submit()"><i class="la la-trash-o"></i></a>
                                                    <form id="form{{ $l->id }}" 
                                                        action="{{ route('lesson.destroy', encryptor('encrypt', $l->id)) }}" 
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>                                                  
                                            </tr>

                                            @empty
                                            <tr>
                                                <th colspan="6" class="text-center">No Course Lesson Found</th>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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

@push('scripts')
<!-- Datatable -->
<script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins-init/datatables.init.js')}}"></script>

@endpush