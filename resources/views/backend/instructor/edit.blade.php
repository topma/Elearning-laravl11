@extends('backend.layouts.app')
@section('title', 'Edit Instructor')

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
                    <h4>Edit Instructor</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('instructor.index')}}">Instructors</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Instructor</a></li>
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
                        <form action="{{route('instructor.update',encryptor('encrypt', $instructor->id))}}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$instructor->id)}}">
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        @if(auth()->user()->role_id == 1)
                                        <input type="email" class="form-control" name="emailAddress"
                                            value="{{old('emailAddress',$instructor->email)}}">
                                        @else
                                        <strong><p>{{$instructor->email}}</p></strong>
                                        <input type="hidden" name="emailAddress" value="{{$instructor->email}}">
                                        @endif
                                    </div>
                                    @if($errors->has('emailAddress'))
                                        <span class="text-danger">{{ $errors->first('emailAddress') }}</span>
                                    @endif
                                </div>
                                
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Role</label>
                                        @if(auth()->user()->role_id == 1)
                                            <select class="form-control" name="roleId">
                                                @forelse ($role as $r)
                                                    <option value="{{ $r->id }}" 
                                                        {{ old('roleId', $instructor->role_id) == $r->id ? 'selected' : '' }}>
                                                        {{ $r->name }}
                                                    </option>
                                                @empty
                                                    <option value="">No Role Found</option>
                                                @endforelse
                                            </select>
                                        @else
                                        <strong>@if($instructor->role_id == 1)
                                                <p> Superadmin </p>
                                                <input type="hidden" name="roleId" value="{{$instructor->role_id}}">
                                            @elseif($instructor->role_id == 2)
                                                <p> Admin </p>
                                                <input type="hidden" name="roleId" value="{{$instructor->role_id}}">
                                            @elseif($instructor->role_id == 3)
                                                <p> Instructor </p>
                                                <input type="hidden" name="roleId" value="{{$instructor->role_id}}">
                                            @else
                                                <p> Unknown Role </p>
                                            @endif
                                        </strong>
                                            
                                        @endif
                                    </div>
                                    @if($errors->has('roleId'))
                                        <span class="text-danger">{{ $errors->first('roleId') }}</span>
                                    @endif
                                </div> 
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title"
                                            value="{{old('title',$instructor->title)}}">
                                    </div>
                                    @if($errors->has('title'))
                                    <span class="text-danger"> {{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="fullName_en"
                                            value="{{old('fullName_en',$instructor->name_en)}}">
                                    </div>
                                    @if($errors->has('fullName_en'))
                                    <span class="text-danger"> {{ $errors->first('fullName_en') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" name="contactNumber_en"
                                            value="{{old('contactNumber_en',$instructor->contact_en)}}">
                                    </div>
                                    @if($errors->has('contactNumber_en'))
                                    <span class="text-danger"> {{ $errors->first('contactNumber_en') }}</span>
                                    @endif
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Designation</label>
                                        <input type="text" class="form-control" name="designation"
                                            value="{{old('designation',$instructor->designation)}}">
                                    </div>
                                    @if($errors->has('designation'))
                                    <span class="text-danger"> {{ $errors->first('designation') }}</span>
                                    @endif
                                </div>   
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Bio</label>
                                        <textarea class="form-control"
                                            name="bio">{{old('bio',$instructor->bio)}}</textarea>
                                    </div>
                                    @if($errors->has('bio'))
                                    <span class="text-danger"> {{ $errors->first('bio') }}</span>
                                    @endif
                                </div>  
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Social Facebook Link</label>
                                        <input type="text" class="form-control" name="social_facebook"
                                            value="{{old('social_facebook',$instructor->social_facebook)}}">
                                    </div>
                                    @if($errors->has('social_facebook'))
                                    <span class="text-danger"> {{ $errors->first('social_facebook') }}</span>
                                    @endif
                                </div>         
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Social Instagram Link</label>
                                        <input type="text" class="form-control" name="social_instagram"
                                            value="{{old('social_instagram',$instructor->social_instagram)}}">
                                    </div>
                                    @if($errors->has('social_instagram'))
                                    <span class="text-danger"> {{ $errors->first('social_instagram') }}</span>
                                    @endif
                                </div>  
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Social Twitter Link</label>
                                        <input type="text" class="form-control" name="social_twitter"
                                            value="{{old('social_twitter',$instructor->social_twitter)}}">
                                    </div>
                                    @if($errors->has('social_twitter'))
                                    <span class="text-danger"> {{ $errors->first('social_twitter') }}</span>
                                    @endif
                                </div>    
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Social LinkedIn Link</label>
                                        <input type="text" class="form-control" name="social_linkedin"
                                            value="{{old('social_linkedin',$instructor->social_linkedin)}}">
                                    </div>
                                    @if($errors->has('social_linkedin'))
                                    <span class="text-danger"> {{ $errors->first('social_linkedin') }}</span>
                                    @endif
                                </div> 
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Social Youtube Link</label>
                                        <input type="text" class="form-control" name="social_youtube"
                                            value="{{old('social_youtube',$instructor->social_youtube)}}">
                                    </div>
                                    @if($errors->has('social_youtube'))
                                    <span class="text-danger"> {{ $errors->first('social_youtube') }}</span>
                                    @endif
                                </div>              
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="1" @if(old('status',$instructor->status)==1) selected
                                                @endif>Active</option>
                                            <option value="0" @if(old('status',$instructor->status)==0) selected
                                                @endif>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                    @if($errors->has('password'))
                                    <span class="text-danger"> {{ $errors->first('password') }}</span>
                                    @endif
                                </div>                                
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <label class="form-label">Image</label>
                                    <div class="form-group fallback w-100">
                                        <input type="file" class="dropify" data-default-file="" name="image">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
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