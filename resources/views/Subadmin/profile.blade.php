@php
    $profile = null;
    if (!empty(Auth::user()->profile_img))
    {
        $profile = asset ('uploads/profiles/'.Auth::user()->profile_img);
    }
    else{
        $profile = asset ('assets/user_avator.png');
    }
@endphp
@extends('Subadmin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">My Profile</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card box-widget widget-user">
                        <div class="widget-user-image mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="{{$profile}}"></div>
                        <div class="card-body text-center pt-2">
                            <div class="pro-user">
                                <h3 class="pro-user-username  mb-1 fs-22">{{Auth::user()->name}}</h3>
                                <h6 class="pro-user-desc text-muted">{{Auth::user()->role}}</h6>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <form action="{{route('subadmin.profile.image')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <input type="file" name="image" class="form-control @if($errors->has('image'))is-invalid @endif">
                                    @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Upload">
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{route('subadmin.profile.password')}}" method="POST">
                        @csrf
                        <div class="card">
                        <div class="card-header ">
                            <div class="card-title">Edit Password</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Old Password</label>
                                <input type="password" name="old_password" value="{{old('old_password')}}" class="form-control @if($errors->has('old_password'))is-invalid @endif">
                                @if ($errors->has('old_password'))
                                    <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @if($errors->has('password'))is-invalid @endif">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control @if($errors->has('password_confirmation'))is-invalid @endif">
                                @if ($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <input type="submit" class="btn btn-primary" value="Update">
                        </div>
                    </div>
                    </form>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <form action="{{route('subadmin.profile.update')}}" method="POST">
                        @csrf
                    <div class="card">
                        <div class="card-header ">
                            <div class="card-title">Edit Profile</div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>Well done!</strong> {{Session::get('success')}}
                                </div>
                            @endif
                                @if (Session::has('danger'))
                                    <div class="alert alert-light-danger" role="alert">
                                        <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                        <strong>Oppss!</strong> {{Session::get('danger')}}
                                    </div>
                                @endif
                            <div class="card-title font-weight-bold">Basic info:</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Your Name</label>
                                        <input type="text" class="form-control @if($errors->has('name'))is-invalid @endif" name="name" placeholder="Your Name" value="{{Auth::user()->name}}">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email address</label>
                                        <input type="email" class="form-control" placeholder="Email" value="{{Auth::user()->email}}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="number" class="form-control @if($errors->has('phone_number'))is-invalid @endif" name="phone_number" placeholder="Phone Number" value="{{Auth::user()->mobile_no}}">
                                        @if ($errors->has('phone_number'))
                                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control @if($errors->has('address'))is-invalid @endif" name="address" placeholder="Home Address" value="{{Auth::user()->address}}">
                                        @if ($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control @if($errors->has('city'))is-invalid @endif" name="city" placeholder="City" value="{{Auth::user()->city}}">
                                        @if ($errors->has('city'))
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Postal Code</label>
                                        <input type="number" class="form-control @if($errors->has('postal_code'))is-invalid @endif" name="postal_code" placeholder="Postal Code" value="{{Auth::user()->postal_code}}">
                                        @if ($errors->has('postal_code'))
                                            <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="form-control select2 @if($errors->has('country'))is-invalid @endif" name="country">
                                                <option>--Select--</option>
                                                <option value="Saudia Arabia" @if(Auth::user()->country == "Saudia Arabia" )selected @endif>Saudia Arabia</option>
                                                <option value="Pakistan" @if(Auth::user()->country == "Pakistan" )selected @endif>Pakistan</option>
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="text-danger">{{ $errors->first('country') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input type="submit" class="btn btn-primary" value="Updated">
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- End Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection