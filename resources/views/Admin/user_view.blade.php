@php
    $profile = null;
    if (!empty($result->profile_img))
    {
        $profile = asset ('uploads/profiles/'.$result->profile_img);
    }
    else{
        $profile = asset ('assets/user_avator.png');
    }
@endphp
@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.profile-view')}}</h4>
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
                                <h3 class="pro-user-username  mb-1 fs-22">{{$result->name}}</h3>
                                <h6 class="pro-user-desc text-muted">{{$result->role}}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                        <div class="card">
                            <div class="card-header ">
                                <div class="card-title">{{__('messages.profile-details')}}</div>
                            </div>
                            <div class="card-body">
                                <div class="card-title font-weight-bold">{{__('messages.basic-info')}}:</div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.name')}}</label>
                                            <input type="text" class="form-control" value="{{$result->name}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.email-address')}}</label>
                                            <input type="email" class="form-control" value="{{$result->email}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.phone-number')}}</label>
                                            <input type="number" class="form-control" value="{{$result->mobile_no}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.address')}}</label>
                                            <input type="text" class="form-control" value="{{$result->address}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.city')}}</label>
                                            <input type="text" class="form-control" value="{{$result->city}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.postal-code')}}</label>
                                            <input type="number" class="form-control" value="{{$result->postal_code}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.country')}}</label>
                                            <input type="text" class="form-control" value="{{$result->country}}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <!-- End Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection