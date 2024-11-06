@extends('backend.layouts.app')
@section('title', 'Student List')

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
                    <h4>Certificate List</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('certificates.index')}}">All Certificates</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"><a href="#list-view" data-toggle="tab"
                            class="nav-link btn-primary mr-1 show active">List View</a></li>
                    <!-- <li class="nav-item"><a href="#grid-view" data-toggle="tab" class="nav-link btn-primary">Grid
                            View</a></li> -->
                </ul>
            </div>
            <div class="col-lg-12">
                <div class="row tab-content">
                    <div id="list-view" class="tab-pane fade active show col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Certificate List </h4>
                                <a href="{{route('certificates.create')}}" class="btn btn-primary">+ Add new</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="example3" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>{{__('#')}}</th>
                                            <th>{{__('Instructor')}}</th>
                                            <th>{{__('Course')}}</th>
                                            <th>{{__('Certificate Type')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $d)
                                        <tr>
                                            <td><img class="rounded-circle" width="35" height="35" 
                                            src="{{asset('uploads/users/' . $d->instructor->image)}}" alt=""></td>
                                            <td>{{ $d->instructor->name_en ?? 'N/A' }}</td>
                                            <td>{{ $d->course->title_en ?? 'N/A' }}</td>
                                            <td>{{ $d->certificate_type }}</td>
                                            <td>
                                                <a href="{{ route('certificates.edit', encryptor('encrypt', $d->id)) }}" class="btn btn-sm btn-primary" title="Edit"><i class="la la-pencil"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger" title="Delete" onclick="$('#form{{ $d->id }}').submit()"><i class="la la-trash-o"></i></a>
                                                <form id="form{{ $d->id }}" action="{{ route('certificates.destroy', encryptor('encrypt', $d->id)) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <th colspan="5" class="text-center">No Certificate Found</th>
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