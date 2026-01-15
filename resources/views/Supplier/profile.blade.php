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
@extends('Supplier.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.my-profile')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row  -->
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
                            @if (Session::has('image'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{__('messages.well-done')}}</strong> {{Session::get('image')}}
                            </div>
                        @endif
                            <form id="profile_img" enctype="multipart/form-data">
                                <div id="add_g_error"></div>
                                <div class="form-group">
                                    <label>{{__('messages.profile-image')}}</label>
                                    <input type="file" name="image" class="form-control @if($errors->has('image'))is-invalid @endif">
                                    @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="button" class="btn btn-primary profile_img" value="{{__('messages.upload')}}">
                                </div>
                            </form>
                        </div>
                    </div>
                    <form id="pass">
                        <div class="card">
                        <div class="card-header ">
                            <div class="card-title">{{__('messages.edit-password')}}</div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('password'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{__('well-done')}}</strong> {{Session::get('password')}}
                            </div>
                            @endif
                            @if (Session::has('old password'))
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{ __('error') }}</strong> {{ Session::get('old password') }}
                            </div>
                            @endif
                            <div id="add_f_error"></div>
                            <div class="form-group">
                                <label class="form-label">{{__('messages.old-password')}}</label>
                                <input type="password" name="old_password" value="{{old('old_password')}}" class="form-control @if($errors->has('old_password'))is-invalid @endif">
                                @if ($errors->has('old_password'))
                                    <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('messages.new-password')}}</label>
                                <input type="password" name="password" class="form-control @if($errors->has('password'))is-invalid @endif">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('messages.confirm-password')}}</label>
                                <input type="password" name="password_confirmation" class="form-control @if($errors->has('password_confirmation'))is-invalid @endif">
                                @if ($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <input type="button" class="btn btn-primary pass" value="{{__('messages.update')}}">
                        </div>
                    </div>
                    </form>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <form id="profile">
                        <div class="card">
                            <div class="card-header ">
                                <div class="card-title">{{__('messages.edit-profile')}}</div>
                            </div>
                            <div class="card-body">
                                @if (Session::has('success'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                                </div>
                                @endif
                                @if (Session::has('danger'))
                                <div class="alert alert-light-danger" role="alert">
                                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                                </div>
                                @endif
                                <div id="add_p_error"></div>
                            <div class="card-title font-weight-bold">{{__('messages.basic-info')}}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.your-name')}}</label>
                                        <input type="text" class="form-control @if($errors->has('name'))is-invalid @endif" name="name" placeholder="Your Name" value="{{Auth::user()->name}}">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.email-address')}}</label>
                                        <input type="email" class="form-control" placeholder="Email" value="{{Auth::user()->email}}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.phone-number')}}</label>
                                        <input type="number" class="form-control @if($errors->has('phone_number'))is-invalid @endif" name="phone_number" placeholder="Phone Number" value="{{Auth::user()->mobile_no}}">
                                        @if ($errors->has('phone_number'))
                                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.address')}}</label>
                                        <input type="text" class="form-control @if($errors->has('address'))is-invalid @endif" name="address" placeholder="Home Address" value="{{Auth::user()->address}}">
                                        @if ($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.city')}}</label>
                                        <input type="text" class="form-control @if($errors->has('city'))is-invalid @endif" name="city" placeholder="City" value="{{Auth::user()->city}}">
                                        @if ($errors->has('city'))
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.postal-code')}}</label>
                                        <input type="number" class="form-control @if($errors->has('postal_code'))is-invalid @endif" name="postal_code" placeholder="Postal Code" value="{{Auth::user()->postal_code}}">
                                        @if ($errors->has('postal_code'))
                                            <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.country')}}</label>
                                        <select class="form-control select2 @if($errors->has('country'))is-invalid @endif" name="country">
                                                <option>{{__('messages.select')}}</option>
                                                <option value="Saudia Arabia" @if(Auth::user()->country == "Saudia Arabia" )selected @endif>{{__('messages.saudia-arabia')}}</option>
                                                <option value="Pakistan" @if(Auth::user()->country == "Pakistan" )selected @endif>{{__('messages.pakistan')}}</option>
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="text-danger">{{ $errors->first('country') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input type="button" class="btn btn-primary profile" value="{{__('messages.updated')}}">
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
@section('query')
    <script type="text/javascript">
                $(Document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                $('.profile_img').click(function () {
                let formData = new FormData($('#profile_img')[0]); // FormData object create karein
                $.ajax({
                    url: '{{route('supplier.profile.image')}}',
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing data
                    contentType: false, // Prevent jQuery from setting content type
                    success: function (response) {
                        console.log(response);
                        if (response.status == "fail") {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors, function (key, error) {
                                $('#add_g_error').append('<li>' + error + '</li>');
                            });
                        } else if (response.status == "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            });


             $('.pass').click(function(){
                $.ajax({
                    url:'{{route('supplier.profile.password')}}',
                    type:'POST',
                    data:$('#pass').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_f_error').html('');
                            $('#add_f_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_f_error').append('<li>'+error+'</li>');
                            })
                        }
                        else if (response.status == "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true
                            });
                        }
                         else {
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                             setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            });

             $('.profile').click(function(){
                $.ajax({
                    url:'{{route('supplier.profile.update')}}',
                    type:'POST',
                    data:$('#profile').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_p_error').html('');
                            $('#add_p_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_p_error').append('<li>'+error+'</li>');
                            })
                        }
                        else if (response.status == "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true
                            });
                        }
                         else {
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                             setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            });
        });
    </script>
@endsection
