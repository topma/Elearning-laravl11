@extends('backend.layouts.app')
@section('title', 'Add Student')

@push('styles')
<!-- Pick date -->
<link rel="stylesheet" href="{{asset('public/vendor/pickadate/themes/default.css')}}">
<link rel="stylesheet" href="{{asset('public/vendor/pickadate/themes/default.date.css')}}">
@endpush

@section('content')

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">

        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Add Certificate</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('certificates.index')}}">Certificates</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0);">Add Certificate</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12 col-sm-12">
                <div class="card"> 
                    <div class="card-header">      
                        <table class="display" style="min-width: 845px" cellpadding="3" cellspacing="3">
                                <thead>
                                                <tr>
                                                    <th>{{__('Default')}}</th>
                                                    <th>{{__('Nova')}}</th>
                                                    <th>{{__('Inspire')}}</th>
                                                    <th>{{__('Eclipse')}}</th>
                                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> <img src="{{asset('uploads/certificates/cert5.png')}}" alt="" width="315" height="217"></td>
                                        <td> <img src="{{asset('uploads/certificates/cert1.png')}}" alt="" width="315" height="217"></td>
                                        <td> <img src="{{asset('uploads/certificates/cert2.png')}}" alt="" width="315" height="217"></td>
                                        <td> <img src="{{asset('uploads/certificates/cert3.png')}}" alt="" width="315" height="217"></td>
                                    </tr>
                                </tbody>
                        </table>  
                    </div>       
                    <div class="card-header"> 
                        <h5 class="card-title">Basic Info</h5>
                    </div>
                    <div class="card-body">                        
                        <form action="{{route('certificates.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Course</label>
                                       <select name="courseId" id="" class="form-control">
                                        @foreach($course as $c)
                                        <option value="{{$c->id}}">{{$c->title_en}}</option>
                                        @endforeach
                                       </select>
                                    </div>
                                    @if($errors->has('courseId'))
                                    <span class="text-danger"> {{ $errors->first('courseId') }}</span>
                                    @endif
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Certificate Type</label>
                                        <select name="certificateType" id="" class="form-control">
                                            @if(auth()->user()->role_id == 1)
                                            <option value="Default">Kings Digi Hub</option>
                                            @endif
                                            <option value="Default">Default</option>
                                            <option value="Nova">Nova</option>
                                            <option value="Inspire">Inspire</option>
                                            <option value="Eclipse">Eclipse</option>
                                        </select>
                                    </div>
                                    @if($errors->has('certificateType'))
                                    <span class="text-danger"> {{ $errors->first('certificateType') }}</span>
                                    @endif
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <label class="form-label">Signature</label>
                                    <div class="form-group fallback w-100">
                                        <input type="file" class="dropify" data-default-file="" name="image">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
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
<script src="{{asset('public/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('public/vendor/pickadate/picker.time.js')}}"></script>
<script src="{{asset('public/vendor/pickadate/picker.date.js')}}"></script>

<!-- Pickdate -->
<script src="{{asset('public/js/plugins-init/pickadate-init.js')}}"></script>
@endpush