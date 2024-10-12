@extends('backend.layouts.app')
@section('title', 'Course Material List')

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
                    <h4>Course Material - {{$lesson->title}}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('course.index')}}">My Courses</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('lesson.show', encryptor('encrypt',$lesson->course_id))}}">Course Lesson</a></li>
                    <li class="breadcrumb-item active"><a href="">All Course Material</a>
                    </li>
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
                                <h4 class="card-title">All Course Materials List </h4>
                                @foreach ($material as $m)
                                <a href="{{route('material.createNew', encryptor('encrypt',$m->lesson?->id))}}" class="btn btn-primary">+ Add new</a>
                                @endforeach
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>{{__('#')}}</th>
                                                <!-- <th>{{__('Title')}}</th> -->
                                                <th>{{__('Lesson')}}</th>
                                                <th>{{__('Material Type')}}</th>
                                                <th>{{__('Content')}}</th>
                                                <th>{{__('Content Url')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($material as $key => $m)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <!-- <td>{{$m->title}}</td> -->
                                                <td>{{$m->lesson?->title}}</td>
                                                <td>
                                                    {{ $m->type == 'video' ? __('Video') : ($m->type == 'document' ?
                                                    __('Document') : __('Quiz')) }}
                                                </td>
                                                <td>
                                                <video width="200" height="100" controls>
                                                    <source src="{{asset('uploads/courses/contents/'.$m->content)}}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                </td>
                                                <td>{{$m->content_url}}</td>
                                                <td>
                                                    <a href="{{route('material.edit', encryptor('encrypt',$m->id))}}"
                                                        class="btn btn-sm btn-primary" title="Edit"><i
                                                            class="la la-pencil"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger"
                                                        title="Delete" onclick="$('#form{{$m->id}}').submit()"><i
                                                            class="la la-trash-o"></i></a>
                                                    <form id="form{{$m->id}}"
                                                        action="{{route('material.destroy', encryptor('encrypt',$m->id))}}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <th colspan="6" class="text-center">No Course Material Found</th>
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